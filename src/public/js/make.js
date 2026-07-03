async function shareCode() {
    const code = document.getElementById("roomCode").textContent.trim();
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
