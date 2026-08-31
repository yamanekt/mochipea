// Service Worker の登録（ホーム画面に追加・オフライン表示のため）
//
// Service Worker は HTTPS か localhost でしか動かない。
// 対応していない環境では何もせず、通常のWebページとして動き続ける。

function swPath() {
    // サブディレクトリ配置（例: /~sys1_26_hijyo/）でも正しい位置を指すよう、
    // blade から渡されたベースURLを使う
    return window.MOCHIPEA_SW_URL || "/sw.js";
}

document.addEventListener("DOMContentLoaded", async () => {
    if (!("serviceWorker" in navigator)) return;

    try {
        await navigator.serviceWorker.register(swPath());
    } catch (error) {
        console.warn("Service Worker を登録できませんでした", error);
    }
});
