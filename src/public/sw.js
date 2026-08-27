// もちぺあ Service Worker
//
// 方針：キャッシュは最小限にする。
// Vite のビルド資産はファイル名にハッシュが付くので積極的にキャッシュしてよいが、
// HTML（＝ログイン状態で中身が変わる）はキャッシュしない。
// 古い画面が出続ける事故を避けるため、迷ったらネットワーク優先にする。

const VERSION = "v1";
const ASSET_CACHE = `mochipea-assets-${VERSION}`;

// 相対パスにしておくとサブディレクトリ配置でも動く
const OFFLINE_URL = "./offline.html";

self.addEventListener("install", (event) => {
    event.waitUntil(
        caches.open(ASSET_CACHE)
            .then((cache) => cache.addAll([OFFLINE_URL]))
            .then(() => self.skipWaiting())
    );
});

self.addEventListener("activate", (event) => {
    // 古いバージョンのキャッシュを消す
    event.waitUntil(
        caches.keys()
            .then((keys) => Promise.all(
                keys.filter((k) => k !== ASSET_CACHE).map((k) => caches.delete(k))
            ))
            .then(() => self.clients.claim())
    );
});

self.addEventListener("fetch", (event) => {
    const request = event.request;

    // GET 以外（フォーム送信など）は一切触らない
    if (request.method !== "GET") return;

    // 別ドメイン（Font Awesome の CDN など）も触らない
    if (new URL(request.url).origin !== self.location.origin) return;

    // 画面遷移：ネットワーク優先。オフラインのときだけ代替ページを出す
    if (request.mode === "navigate") {
        event.respondWith(
            fetch(request).catch(() => caches.match(OFFLINE_URL))
        );
        return;
    }

    // ビルド資産と画像：一度取れたら次からキャッシュを使う
    const path = new URL(request.url).pathname;
    if (path.includes("/build/") || path.includes("/images/")) {
        event.respondWith(
            caches.match(request).then((cached) => {
                if (cached) return cached;

                return fetch(request).then((response) => {
                    // 正常なレスポンスだけ保存する
                    if (response.ok) {
                        const copy = response.clone();
                        caches.open(ASSET_CACHE).then((cache) => cache.put(request, copy));
                    }
                    return response;
                });
            })
        );
    }
});

// ===== プッシュ通知 =====
// サーバーから届いた通知を表示する
self.addEventListener("push", (event) => {
    let payload = {};
    try {
        payload = event.data ? event.data.json() : {};
    } catch {
        payload = { body: event.data ? event.data.text() : "" };
    }

    const title = payload.title || "もちぺあ";
    const options = {
        body: payload.body || "",
        icon: "./images/icons/icon-192.png",
        badge: "./images/icons/icon-192.png",
        data: { url: payload.url || "./" },
        // 同じタグの通知は上書きする（同じ相手から連続で来ても積み上がらない）
        tag: payload.tag || "mochipea",
    };

    event.waitUntil(self.registration.showNotification(title, options));
});

// 通知をタップしたとき：既に開いているタブがあればそれを使う
self.addEventListener("notificationclick", (event) => {
    event.notification.close();
    const target = (event.notification.data && event.notification.data.url) || "./";

    event.waitUntil(
        self.clients.matchAll({ type: "window", includeUncontrolled: true })
            .then((clients) => {
                for (const client of clients) {
                    if ("focus" in client) {
                        client.navigate(target);
                        return client.focus();
                    }
                }
                return self.clients.openWindow(target);
            })
    );
});
