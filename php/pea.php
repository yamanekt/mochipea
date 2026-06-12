<?php
//ホスト側
session_start();

$pdo = new PDO('mysql:host=localhost;dbname=emergency_exit;charset=utf8', 'root', 'パスワード');

$my_id = $_SESSION['user_id'];
$match_code = $_POST['match_code'];


// 自分のコードを登録(ホスト)
$sql = "UPDATE users SET match_code = :code, role = 'host' WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':code', $match_code);
$stmt->bindValue(':id', $my_id);
$stmt->execute();
