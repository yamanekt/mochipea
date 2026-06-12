<?php
$result = [
  "status"  => true,
  "message" => null,
  "result"  => false,
];

// POST送信以外は不正な扱いにする
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  exit("不正なアクセスです");
}

// POSTデータ取得
$name      = filter_input(INPUT_POST, "name");
$email     = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
$password  = filter_input(INPUT_POST, "password");
$password2 = filter_input(INPUT_POST, "password2");

// 名前チェック
if ($name !== null) {
  $name = trim($name);
}

if ($name === null || $name === "") {
  $result["status"]  = false;
  $result["message"] = "名前を入力してください。";
}

// メールチェック
if ($result["status"]) {
  if ($email === null || $email === false) {
    $result["status"]  = false;
    $result["message"] = "メールアドレスを正しく入力してください。";
  }
}

// パスワードチェック
if ($result["status"]) {
  if ($password === null || $password === "") {
    $result["status"]  = false;
    $result["message"] = "パスワードを入力してください。";
  } elseif ($password2 === null || $password2 === "") {
    $result["status"]  = false;
    $result["message"] = "確認用パスワードを入力してください。";
  } elseif ($password !== $password2) {
    $result["status"]  = false;
    $result["message"] = "パスワードが一致しません。";
  } elseif (mb_strlen($password) < 8) {
    $result["status"]  = false;
    $result["message"] = "パスワードは8文字以上にしてください。";
  } elseif (!preg_match("/^[a-zA-Z0-9]+$/", $password)) {
    $result["status"]  = false;
    $result["message"] = "パスワードは英数字で入力してください。";
  }
}

// DB登録
if ($result["status"]) {
  try {
    $dsn = "mysql:host=localhost;dbname=mochipea_db;charset=utf8mb4";
    $db = new PDO($dsn, "root", "root");

    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $password_hash = password_hash($password, PASSWORD_ARGON2ID);

    $sql = "INSERT INTO users
              (name, email, password)
            VALUES
              (:name, :email, :password)";

    $stmt = $db->prepare($sql);

    $stmt->bindValue(":name", $name, PDO::PARAM_STR);
    $stmt->bindValue(":email", $email, PDO::PARAM_STR);
    $stmt->bindValue(":password", $password_hash, PDO::PARAM_STR);

    $result["result"] = $stmt->execute();

    if ($result["result"]) {
      $result["message"] = "アカウント作成に成功しました！";
    } else {
      $result["status"]  = false;
      $result["message"] = "アカウント作成に失敗しました。";
    }

  } catch (PDOException $e) {
    $result["status"]  = false;

    // メールアドレス重複エラー
    if ($e->getCode() === "23000") {
      $result["message"] = "このメールアドレスはすでに登録されています。";
    } else {
      $result["message"] = "DBエラー：" . $e->getMessage();
    }
  }
}
?>