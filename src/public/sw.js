// もちぺあ Service Worker
//
// 方針：キャッシュは最小限にする。
// Vite のビルド資産はファイル名にハッシュが付くので積極的にキャッシュしてよいが、
// HTML（＝ログイン状態で中身が変わる）はキャッシュしない。
// 古い画面が出続ける事故を避けるため、迷ったらネットワーク優先にする。

const VERSION = "v2";
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
