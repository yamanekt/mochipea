// Service Worker の登録と、通知の許可まわり
//
// Service Worker は HTTPS か localhost でしか動かない。
// 対応していない環境では何もせず、通常のWebページとして動き続ける。

function swPath() {
    // サブディレクトリ配置（例: /~sys1_26_hijyo/）でも正しい位置を指すよう、
    // blade から渡されたベースURLを使う
    return window.MOCHIPEA_SW_URL || "/sw.js";
}

async function registerServiceWorker() {
    if (!("serviceWorker" in navigator)) return null;

    try {
        return await navigator.serviceWorker.register(swPath());
    } catch (error) {
        console.warn("Service Worker を登録できませんでした", error);
        return null;
    }
}

// 通知が使える環境かどうか
export function canNotify() {
    return "Notification" in window && "serviceWorker" in navigator && "PushManager" in window;
}

/**
 * 通知の許可を求める。
 * ブラウザの仕様上、ユーザーの操作（クリックなど）の中から呼ぶ必要がある。
 */
export async function requestNotificationPermission() {
    if (!canNotify()) return "unsupported";

    if (Notification.permission === "granted") return "granted";
    if (Notification.permission === "denied") return "denied";

    return await Notification.requestPermission();
}

// VAPID公開鍵は base64url で渡されるので、Push API が求める Uint8Array に直す
function urlBase64ToUint8Array(base64String) {
    const padding = "=".repeat((4 - (base64String.length % 4)) % 4);
    const base64 = (base64String + padding).replace(/-/g, "+").replace(/_/g, "/");
    const raw = window.atob(base64);
    return Uint8Array.from([...raw].map((c) => c.charCodeAt(0)));
}

/** 購読を作ってサーバーに預ける。鍵が未設定なら何もしない */
async function subscribeToPush(registration) {
    const publicKey = window.MOCHIPEA_VAPID_KEY;
    if (!publicKey) return false;

    let subscription = await registration.pushManager.getSubscription();

    if (!subscription) {
        subscription = await registration.pushManager.subscribe({
            userVisibleOnly: true, // 届いた通知は必ず表示する（ブラウザの必須条件）
            applicationServerKey: urlBase64ToUint8Array(publicKey),
        });
    }

    const json = subscription.toJSON();

    const response = await fetch(window.MOCHIPEA_SUBSCRIBE_URL, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.content ?? "",
        },
        body: JSON.stringify({
            endpoint: subscription.endpoint,
            publicKey: json.keys.p256dh,
            authToken: json.keys.auth,
        }),
    });

    return response.ok;
}

document.addEventListener("DOMContentLoaded", () => {
    registerServiceWorker();

    // 通知を有効にするボタン（設置されている画面でだけ動く）
    const button = document.querySelector("[data-enable-notification]");
    if (!button) return;

    if (!canNotify()) {
        button.disabled = true;
        button.textContent = "この端末では使えません";
        return;
    }

    if (Notification.permission === "granted") {
        button.textContent = "通知はオンです";
        button.disabled = true;
        return;
    }

    button.addEventListener("click", async () => {
        const result = await requestNotificationPermission();

        if (result === "granted") {
            button.textContent = "通知をオンにしました";
            button.disabled = true;

            const registration = await navigator.serviceWorker.ready;

            // 購読をサーバーに預ける（アプリを閉じていても届くようにする）
            try {
                await subscribeToPush(registration);
            } catch (error) {
                console.warn("プッシュの購読に失敗しました", error);
            }

            // 動作確認を兼ねて1件出す
            registration.showNotification("もちぺあ", {
                body: "通知をオンにしました。相手の記録をお知らせします。",
                icon: "./images/icons/icon-192.png",
            });
        } else if (result === "denied") {
            button.textContent = "ブラウザの設定から許可してください";
            button.disabled = true;
        }
    });
});
