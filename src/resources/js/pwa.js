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

            // 動作確認を兼ねて1件出す
            const registration = await navigator.serviceWorker.ready;
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
