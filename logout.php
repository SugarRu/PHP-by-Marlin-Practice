<?php

session_start();

unset($_SESSION['user']);
setcookie('email', '', time()-3600);
setcookie('pswrd', '', time()-3600);

header('Location:login.php'); 