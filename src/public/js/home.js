
document.addEventListener("DOMContentLoaded", () => {
    const intro = document.querySelector(".intro");

    // 一度再生済みなら表示しない
    if (sessionStorage.getItem("introPlayed")) {
        intro.style.display = "none";
    } else {
        // 初回だけ再生
        sessionStorage.setItem("introPlayed", "true");

        // アニメーション終了後に非表示
        setTimeout(() => {
            intro.style.display = "none";
        }, 2500);
    }
});
