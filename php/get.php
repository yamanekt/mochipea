<?php

require "db.php";

$room = $_GET["room"];

$stmt = $pdo->prepare(
    "SELECT content FROM rooms WHERE id=?"
);

$stmt->execute([$room]);

$data = $stmt->fetch();

echo $data["content"];