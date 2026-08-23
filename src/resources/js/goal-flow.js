// 目標作成フローの入力補助

document.addEventListener("DOMContentLoaded", () => {

    // ===== 文字数カウンタ =====
    document.querySelectorAll("[data-counter-target]").forEach((input) => {
        const output = document.getElementById(input.dataset.counterTarget);
        if (!output) return;

        const update = () => {
            output.textContent = String(input.value.length);
        };
        input.addEventListener("input", update);
        update();
    });

    // ===== 数字だけ受け付ける =====
    document.querySelectorAll("[data-numeric-only]").forEach((input) => {
        input.addEventListener("input", () => {
            input.value = input.value.replace(/[^0-9]/g, "");
        });
    });

    // ===== 「その他」を選んだときだけ自由入力を出す =====
    const reveals = document.querySelectorAll("[data-reveal]");
    reveals.forEach((radio) => {
        const target = document.getElementById(radio.dataset.reveal);
        if (!target) return;

        // 同じ name のラジオが切り替わったら都度みなおす
        const group = document.querySelectorAll(`input[name="${radio.name}"]`);
        const update = () => {
            target.hidden = !radio.checked;
            if (radio.checked) target.querySelector("input")?.focus();
        };
        group.forEach((r) => r.addEventListener("change", update));
        update();
    });

    // ===== 期限のクイック選択 =====
    document.querySelectorAll("[data-set-date]").forEach((button) => {
        button.addEventListener("click", () => {
            const input = document.getElementById(button.dataset.target);
            if (!input) return;
            input.value = button.dataset.setDate;

            document.querySelectorAll("[data-set-date]").forEach((b) => {
                b.classList.toggle("is-selected", b === button);
            });
        });
    });
});
