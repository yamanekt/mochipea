// ログイン・新規登録のパスワード表示切替
// CSS で ::-ms-reveal を消しているため、代わりの導線をこちらで用意する

document.addEventListener("DOMContentLoaded", () => {
    const buttons = document.querySelectorAll("[data-toggle-password]");

    buttons.forEach((button) => {
        button.addEventListener("click", () => {
            const input = document.getElementById(button.dataset.togglePassword);
            if (!input) return;

            const willShow = input.type === "password";
            input.type = willShow ? "text" : "password";
            button.textContent = willShow ? "隠す" : "表示";
            button.setAttribute("aria-pressed", String(willShow));
        });
    });
});
