<?php
/* session_start(); */
require 'db.php';

/* Подготовка переменных */
$email = $_SESSION['email'] = $_POST['email'];
$pswrd = trim($_POST['password']);


/* Подготовка запроса для сверки данных */
$sql = 'SELECT email, password FROM registration WHERE email = :email';
$params = [':email' => $email];
$STH = $pdo->prepare($sql);
$STH->execute($params);
$login_data = $STH->fetch(PDO::FETCH_ASSOC);


/* Валидация формы */
  if (!$email) {
    $_SESSION['message_email'] = 'Введите почту';
    header('Location: login.php');

  } elseif ( !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ) {
    $_SESSION['message_email'] = 'Неверный формат';
    header('Location: login.php');  

  } elseif (!$pswrd) {
    $_SESSION['message_pswrd'] = 'Введите пароль';
    header('Location: login.php');  
    
   
  }  else  {

    /* Свверка данных */
    if (!$login_data) {
      $_SESSION['message_email'] = 'Данная почта не зарегестрированна';
      header('Location: login.php');

    } elseif (!password_verify($pswrd, $login_data['password'])) { // сверка хеша пароля {
      $_SESSION['message_pswrd'] = 'Неверный пароль';
      header('Location: login.php');

    } else {
      $_SESSION['user'] = $login_data['email'];

      unset($_SESSION['message_email'],
       $_SESSION['email'],
       $_SESSION['message_pswrd']
      );      
      
      header('Location: index.php');   
    }   
  }
  /* Конец сверки данных */ 
/*  */



  
  


 

 