// 部屋番号を共有する（Web Share API が使えなければクリップボードにコピー）
async function shareCode() {
    const codeBox = document.getElementById("roomCode");
    if (!codeBox) {
        return;
    }

    const code = codeBox.textContent.trim();
    const text = `部屋番号は${code}です。別のアカウントでログインして、この番号を入力してください。`;

    if (navigator.share) {
        await navigator.share({
            title: "部屋番号",
            text: text,
        });

        return;
    }

    await navigator.clipboard.writeText(text);
}

document.addEventListener("DOMContentLoaded", () => {
    const shareBtn = document.querySelector(".share-btn");
    if (!shareBtn) {
        return;
    }

    shareBtn.addEventListener("click", shareCode);
});
