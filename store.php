<?php 
session_start();

$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
$pdo = new PDO('mysql:host=localhost;dbname=marlin_php', 'root', '', $options);

/* Валидация формы */
$user_name = $user_comment = "";
if (isset($_POST['name']) && isset($_POST['text'])) {
  $user_name = input_check($_POST['name']);
  $user_comment = input_check($_POST['text']);
}

function input_check($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
/* -- // --  */

$sql = "INSERT INTO comments (user_name, comment_date, user_comment) VALUES (:user_name, :comment_date, :user_comment)";
$STH = $pdo->prepare($sql);
$STH->execute(array (
  'user_name' => $user_name,
  'comment_date' => date('Y,m,d'),
  'user_comment' => $user_comment
));

$_SESSION['success'] = 'Комментарий успешно добавлен';
header('Location: /');

