<?php 
session_start();

$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
$pdo = new PDO('mysql:host=localhost;dbname=marlin_php', 'root', '', $options);

/* проверка на заполнение формы */
$sql = "INSERT INTO comments (user_name, comment_date, user_comment) VALUES (:user_name, :comment_date, :user_comment)";
$STH = $pdo->prepare($sql);
$STH->execute(array (
  'user_name' => $_POST['name'],
  'comment_date' => date('Y,m,d'),
  'user_comment' => $_POST['text']
));
$_SESSION['success'] = 1;
header('Location: /');