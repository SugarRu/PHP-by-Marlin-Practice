<?php 

session_start();
$_SESSION['name'] = $_POST['name']; 

$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
$pdo = new PDO('mysql:host=localhost;dbname=marlin_php', 'root', '', $options);

/* Валидация формы */

function input_check($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function valid_name($data) {
  return preg_match('/^[A-z0-9][3,10]$/', $data); 
}

$user_name = $user_comment = "";
if (isset($_POST['name']) && isset($_POST['text'])) {
  $user_name = input_check($_POST['name']);
  $user_comment = input_check($_POST['text']);
}

if (empty($_POST['textarea'])) {
  $_SESSION['valid_text'] = 'Введите комментарий';
} else {
$sql = "INSERT INTO comments (user_name, comment_date, user_comment) VALUES (:user_name, :comment_date, :user_comment)";
$STH = $pdo->prepare($sql);
$STH->execute(array (
  'user_name' => $user_name,
  'comment_date' => date('Y,m,d'),
  'user_comment' => $user_comment
));

$_SESSION['success'] = 'Комментарий успешно добавлен';
}

header('Location: /');



