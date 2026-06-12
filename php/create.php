<?php

require "db.php";

$roomId = strtoupper(substr(bin2hex(random_bytes(4)), 0, 6));

$stmt = $pdo->prepare(
    "INSERT INTO rooms (id) VALUES (?)"
);

$stmt->execute([$roomId]);

header("Location: room.php?room=$roomId");
exit;