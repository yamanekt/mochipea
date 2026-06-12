<?php

require "db.php";

$room = $_POST["room"];
$content = $_POST["content"];

$stmt = $pdo->prepare(
    "UPDATE rooms SET content=? WHERE id=?"
);

$stmt->execute([$content, $room]);
