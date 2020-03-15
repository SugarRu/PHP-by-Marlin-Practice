<?php
session_start();
require 'db.php';

/* Подготовка переменных */
$name = $_SESSION['name'] = $_POST['name'];    
$email = $_SESSION['email'] = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
$pswrd = $_SESSION['pswrd'] = $_POST['password'];  
$pswrd_confirm  = $_POST['password_confirmation'];


/* Провверка почты на дубликат */
  $sql = 'SELECT email FROM registration WHERE email = :email';
  $STH = $pdo->prepare($sql);
  $STH->execute([':email' => $email]);
  
  $email_taken = $STH->fetch(PDO::FETCH_OBJ);  
/*  */


/* Валидация формы и запись в БД */

  /* Проверка на заполнение поля Name */
  if (!$name) {
    $_SESSION['message_name'] = 'Введите имя';    
    header('Location: register.php');

    /* Проверка на заполнение поля E-mail */
  } elseif (!$email) {  
    $_SESSION['message_email'] = 'Укажите почту';
    header('Location: register.php'); 

    /* Проверка почты на дубликат */
  } elseif ($email_taken) {                
    $_SESSION['message_email_taken'] = 'Такая почту уже занята';
    header('Location: register.php');  

    /* Проверка на заполнение поля Password */
  } elseif (!$pswrd) {
    $_SESSION['message_pswrd'] = 'Создайте пароль';
    header('Location: register.php');   

    /* Проверка пароля на количество символов */    
  } elseif (strlen($pswrd) <= 5) {                
    $_SESSION['message_pswrd_length'] = 'Пароль не должен быть короче 6 символов';
    header('Location: register.php');   

    /* Проверка на заполнение поля Password Confirm*/
  } elseif (!$pswrd_confirm) {
    $_SESSION['message_pswrd_empty'] = 'Продублируйте пароль';
    header('Location: register.php'); 
    
    /* Проверка на совпадение паролей */
  } elseif ($pswrd != $pswrd_confirm) {                
    $_SESSION['message_pswrd_confirm'] = 'Пароли не совпадают';
    header('Location: register.php');       
    
  /* Запись в БД */
  } else {
    $sql = "INSERT INTO registration (name, email, password) VALUES (:name, :email, :password)";
    $STH = $pdo->prepare($sql);

    /* Хеширование пароля перед записью*/
    $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $STH->execute(array (
      'name' => $_POST['name'],
      'email' => $email,
      'password' => $hash
    ));  

    $_SESSION['success'] = 'Успешная регистрация';
    header('Location: register.php');    
  }
/*  */