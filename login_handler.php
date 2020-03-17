<?php
require 'db.php';

/* Подготовка переменных */
$email = $_SESSION['email'] = trim($_POST['email']);
$pswrd = trim($_POST['password']);
$checkbox = $_POST['remember'];

/* Подготовка запроса для сверки данных */
$sql = 'SELECT email, password FROM registration WHERE email = :email';
$params = [':email' => $email];
$STH = $pdo->prepare($sql);
$STH->execute($params);
$login_data = $STH->fetch(PDO::FETCH_ASSOC);



# Проверка наличия сессии
if ($_SESSION['user']) {  
  header('Location: index.php');   
  
} else {# Проверка наличия куки
  if ($_COOKIE['pswrd'] && $_COOKIE['email']) {

    # Поиск пользователя в БД по куки и начало новой сессии

      /* Подготовка запроса для сверки данных */
        $sql = 'SELECT email, password FROM registration WHERE email = :email';
        $params = [':email' => $_COOKIE['email']];
        $STH = $pdo->prepare($sql);
        $STH->execute($params);
        $login_data = $STH->fetch(PDO::FETCH_ASSOC);
      /*  */
        
      if ( $_COOKIE['pswrd'] == $login_data['password'] )   { 
        $_SESSION['user'] = $login_data['email'];
        header('Location: index.php');
      } 
    #   
  } else {#авторизация

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

          } elseif (!password_verify($pswrd, $login_data['password'])) { // Cверка хеша пароля {
            $_SESSION['message_pswrd'] = 'Неверный пароль';
            header('Location: login.php');

          } else {
            $_SESSION['user'] = $login_data['email'];

            unset($_SESSION['message_email'],
            $_SESSION['email'],
            $_SESSION['message_pswrd']
            );
      
            #Установка куки по чекбоксу
              if ($checkbox == 1) {
                setcookie('email', $email, time() + (3600 * 24 * 30));                
                setcookie('pswrd', $login_data['password'], time() + (3600 * 24 * 30));

              } else {
                setcookie('email', '', time()-3600);
                setcookie('pswrd', '', time()-3600);
              }
            #
            header('Location: index.php');   
          }  
        /* Конец сверки данных */   
      }         
    /* Конец валидации формы */
  } 
}