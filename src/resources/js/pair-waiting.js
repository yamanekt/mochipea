// 待っている部屋：番号の共有と、取り消しの確認

function flash(button, message) {
    const original = button.textContent;
    button.textContent = message;
    setTimeout(() => {
        button.textContent = original;
    }, 1600);
}

// クリップボードが使えない環境（非HTTPS・権限拒否）向けの手動コピー
function copyFallback(text) {
    const area = document.createElement("textarea");
    area.value = text;
    area.setAttribute("readonly", "");
    area.style.position = "fixed";
    area.style.opacity = "0";
    document.body.appendChild(area);
    area.select();

    let ok = false;
    try {
        ok = document.execCommand("copy");
    } catch {
        ok = false;
    }
    area.remove();
    return ok;
}

async function shareCode(code, button) {
    const text = `部屋番号は${code}です。もちぺあで、この番号を入力して参加してください。`;

    if (navigator.share) {
        try {
            await navigator.share({ title: "部屋番号", text });
            return;
        } catch (error) {
            // ユーザーが共有シートを閉じただけなら、そのまま終わる。
            // それ以外（未対応・権限拒否など）は下のコピー処理へ進む
            if (error && error.name === "AbortError") return;
        }
    }

    try {
        await navigator.clipboard.writeText(text);
        flash(button, "コピーしました");
        return;
    } catch {
        // clipboard API が使えない環境へ続く
    }

    flash(button, copyFallback(text) ? "コピーしました" : "コピーできませんでした");
}

document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("[data-share-code]").forEach((button) => {
        button.addEventListener("click", () => shareCode(button.dataset.shareCode, button));
    });

    // 目標ごと消える操作なので、送信前に確認する
    document.querySelectorAll("[data-confirm]").forEach((form) => {
        form.addEventListener("submit", (event) => {
            if (!window.confirm(form.dataset.confirm)) {
                event.preventDefault();
            }
        });
    });
});
