// 部屋に参加：4桁コードの入力補助と、飛び出すキャラクター演出

// ===== 4桁コード =====
// 見た目は1桁ずつの入力欄だが、サーバーへは1つの code として送る
document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("[data-code-form]");
    if (!form) return;

    const digits = Array.from(form.querySelectorAll("[data-code-digit]"));
    const hidden = form.querySelector("[data-code-value]");
    const submit = form.querySelector("[data-code-submit]");
    if (!digits.length || !hidden) return;

    const sync = () => {
        const code = digits.map((d) => d.value).join("");
        hidden.value = code;
        if (submit) {
            const filled = code.length === digits.length;
            submit.disabled = !filled;
        }
    };

    digits.forEach((input, index) => {
        input.addEventListener("input", () => {
            // 数字以外は受け付けない
            input.value = input.value.replace(/[^0-9]/g, "").slice(0, 1);
            if (input.value && index < digits.length - 1) {
                digits[index + 1].focus();
            }
            sync();
        });

        // 空欄で BackSpace を押したら1つ前に戻る
        input.addEventListener("keydown", (event) => {
            if (event.key === "Backspace" && !input.value && index > 0) {
                digits[index - 1].focus();
            }
        });

        // 4桁まとめて貼り付けられたら1桁ずつ配る
        input.addEventListener("paste", (event) => {
            const text = (event.clipboardData || window.clipboardData).getData("text");
            const numbers = text.replace(/[^0-9]/g, "").slice(0, digits.length);
            if (!numbers) return;
            event.preventDefault();
            numbers.split("").forEach((n, i) => {
                digits[i].value = n;
            });
            digits[Math.min(numbers.length, digits.length - 1)].focus();
            sync();
        });
    });

    sync();
});

// ===== 飛び出すキャラクター =====
const images = window.POP_IMAGES ?? [];

function popCharacter() {
    const area = document.getElementById("pop-area");
    if (!area || !images.length) return;

    const img = document.createElement("img");
    img.src = images[Math.floor(Math.random() * images.length)];
    img.className = "pop-character";
    img.alt = "";

    const start = Math.random() * 80 + 10;
    img.style.left = `${start}%`;
    img.style.setProperty("--x", `${(Math.random() - 0.5) * 200}px`);
    img.style.setProperty("--x2", `${(Math.random() - 0.5) * 300}px`);
    img.style.setProperty("--x3", `${(Math.random() - 0.5) * 400}px`);

    area.appendChild(img);
    setTimeout(() => img.remove(), 2600);
}

document.addEventListener("DOMContentLoaded", () => {
    if (!document.getElementById("pop-area") || !images.length) return;
    setInterval(popCharacter, 900);
});
