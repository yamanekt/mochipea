<?php

require "db.php";

$roomId = $_GET["room"] ?? "";

$stmt = $pdo->prepare(
    "SELECT * FROM rooms WHERE id=?"
);

$stmt->execute([$roomId]);

$room = $stmt->fetch();

if (!$room) {
    die("部屋が存在しません");
}
?>


<script>

function load() {
    fetch("get.php?room=<?= $roomId ?>")
        .then(r => r.text())
        .then(data => {
            document.getElementById("result").innerText = data;
        });
}

function save() {

    let content =
        document.getElementById("content").value;

    fetch("save.php", {
        method: "POST",
        headers: {
            "Content-Type":
            "application/x-www-form-urlencoded"
        },
        body:
            "room=<?= $roomId ?>&content="
            + encodeURIComponent(content)
    }).then(load);
}

load();
setInterval(load, 1000);

</script>

