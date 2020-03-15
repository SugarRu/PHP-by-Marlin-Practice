<?php
/* session_start(); */
require 'db.php';

/* Подготовка переменных */
$email = trim($_POST['email']);
$pswrd = trim($_POST['password']);


/* Подготовка запроса для сверки данных */
$sql = 'SELECT email, password FROM registration WHERE email = :email';
$params = [':email' => $email];
$STH = $pdo->prepare($sql);
$STH->execute($params);
$login_data = $STH->fetch(PDO::FETCH_ASSOC);


/* Свверка данных */
  if ($login_data) {

    if (password_verify($pswrd, $login_data['password'])) { // сверка хеша пароля

      $_SESSION['user'] = $login_data['email'];
      unset($_SESSION['message']);      
      header('Location: index.php');
      
    } else {

      $_SESSION['message'] = 'Неверный пароль';
      header('Location: login.php');
    }
    
  } else {

    $_SESSION['message'] = 'Такая почта не зарегестрированна';
    header('Location: login.php');
  }
/*  */