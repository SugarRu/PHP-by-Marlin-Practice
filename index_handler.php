<?php 

require 'db.php';

$name = $_POST['name'];
$message = $_POST['text'];

/* Валидация формы */
if (!$message || !$name) {

  $_SESSION['message'] = 'Поля не должны быть пустыми';
  header ('Location: index.php');

} else {

  $message = htmlentities($message);  // защита от инъекции в комментариях

  /* Запись данных в БД */
  $sql = "INSERT INTO comments(user_name, comment_date, user_comment) VALUES (:user_name, :comment_date, :user_comment)";
  
  $param = [
    ':user_name' => $name,
    ':comment_date' => date('Y,m,d'),
    ':user_comment' => $message
  ];

  $STH =$pdo->prepare($sql);
  $STH->execute($param);

  $_SESSION['success'] = 'Комментарий успешно добавлен';
  header ('Location: index.php');
}

/* Вывод комментариев */
$sql = 'SELECT * FROM comments ORDER BY id DESC';
$STH = $pdo->prepare($sql);
$STH->execute();
$_SESSION['comments'] = $STH->fetchALL(PDO::FETCH_ASSOC); 






