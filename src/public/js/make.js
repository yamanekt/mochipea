window.onload = function () {

    const code =
        Math.floor(1000 + Math.random() * 9000);

    document.getElementById("roomCode").textContent = code;
};

async function shareCode() {

    const code =
        document.getElementById("roomCode").textContent;

    const text =
        `あなたのコードは${code}です`;

    if (navigator.share) {

        await navigator.share({
            title: "ルームコード",
            text: text
        });

    } else {

        await navigator.clipboard.writeText(text);

        document.getElementById("copyMessage").textContent =
            "コピーしました！";
    }
}
