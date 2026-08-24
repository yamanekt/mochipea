// 待っている部屋：番号の共有（Web Share が使えなければクリップボードへ）

async function shareCode(code, button) {
    const text = `部屋番号は${code}です。もちぺあで、この番号を入力して参加してください。`;

    if (navigator.share) {
        try {
            await navigator.share({ title: "部屋番号", text });
            return;
        } catch {
            // 共有シートを閉じただけなので、そのまま何もしない
            return;
        }
    }

    await navigator.clipboard.writeText(text);
    const original = button.textContent;
    button.textContent = "コピーしました";
    setTimeout(() => {
        button.textContent = original;
    }, 1600);
}

document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("[data-share-code]").forEach((button) => {
        button.addEventListener("click", () => shareCode(button.dataset.shareCode, button));
    });
});
