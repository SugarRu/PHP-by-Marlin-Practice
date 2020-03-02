<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Registration</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="css/app.css" rel="stylesheet">
</head>

<?php     
    $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
    $pdo = new PDO('mysql:host=localhost;dbname=marlin_php', 'root', '', $options);     

    /* Вывод сообщения об ошибке */
    function set_flash($form_type, $message) {
        $_SESSION[$form_type] = $message;
    }

    function get_flash($form_type) {
        echo $_SESSION[$form_type];
        unset($_SESSION[$form_type]);
    }

    /* Проверка на заполнение поля Name */
    function required_name($name) {
        if ($name == '') {
            return 'Введите имя';
        }
    }
    $message_name = required_name($_POST['name']);    

    /* Проверка на заполнение поля E-mail */

    function required_email($email) {
        if ($email == '') {
            return 'Укажите почтовый адрес';
        }
    }
    $message_email = required_email($_POST['email']);
    var_dump($message_email);

    /* Провверка почты на дубликат */
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $STH = $pdo->prepare("SELECT email FROM registration WHERE email = ?");
    $STH->execute([$email]);
    $email_taken = $STH->fetch();
    
    function email_taken($email) {       
        if ($email == '') {            
        } elseif ($email[0] == $_POST['email']) {
            return 'Такая почта уже зарегистрирована';            
        }        
    }
    $message_email_taken = email_taken($email_taken); 
      
    
    

   /* Проверка на заполнение поля Password */
   function required_pswrd($pswrd) {
        if ($pswrd== '') {
            return 'Создайте пароль';
        }
    }
   $message_pswrd = required_pswrd($_POST['password']);  

   /* Проверка пароля на количество символов */
    function pswrd_length($pswrd) {
        if (strlen($pswrd) <= 5) {
            return 'Пароль не должен быть короче 6 символов';
        }
    }
    $message_pswrd_length = pswrd_length($_POST['password']);    

   /* Проверка на совпадение паролей */
    function pswrd_confirm($pswrd, $pswrd_conf) {
        if ($pswrd != $pswrd_conf && $pswrd != '') {
            return 'Пароли не совпадают';
        }
    }   
    $message_pswrd_confirm = pswrd_confirm($_POST['password'], $_POST['password_confirmation']);

    /* Запись в БД */ 
    if ($_SESSION['name_empty'] == $_SESSION['email_empty'] && $_SESSION['email_exists'] == $_SESSION['pswrd_empty'] && $_SESSION['pswrd_length'] == $_SESSION['pswrd_ntmchd'])  {
        
        $sql = "INSERT INTO registration (name, email, password) VALUES (:name, :email, :password)";
        $STH = $pdo->prepare($sql);

        /* Валидация E-mail */
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

        /* Хеширование пароля перед записью*/
        $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
       
        $STH->execute(array (
            'name' => $_POST['name'],
            'email' => $email,
            'password' => $hash
        ));  

        $_SESSION['success'] = 'Успешная регистрация'; /* ПОЧЕМУ NULL */

        unset($_SESSION['pswrd_match']);
        unset($_SESSION['name']);
        unset($_SESSION['success']);
        unset($_SESSION['email']);
        unset($_SESSION['password']);
        unset($_SESSION['password_confirmation']);

    }

   
?>



<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="index.php">
                    Project
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                            <li class="nav-item">
                                <a class="nav-link" href="login.php">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="register.php">Register</a>
                            </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">Register</div>

                            <?php
                            
                            if ($_SESSION['success']) {
                                echo '<span class="alert-success text-md-center" role="alert"><strong> '
                                . $_SESSION['success'] . '</strong></span>'; 
                            }    
                                                                  
                            ?>
                            

                            <div class="card-body">
                                <form method="POST" action="">

                                    <div class="form-group row">
                                        <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>

                                        <div class="col-md-6">
                                            <input id="name" type="text" class="form-control <?php echo required_name($_POST['name']) ? '@error(\'name\') is-invalid @enderror': '';?>" name="name" autofocus value=""> 
                                             
                                            <?php 
                                                if (required_name($_POST['name'])) {
                                                    set_flash($_POST['name'], $message_name);
                                                    echo '<span class="invalid-feedback" role="alert"><strong>'; 
                                                    get_flash($_POST['name']); 
                                                    echo '</strong></span>';
                                                }                
                                                                    
                                                                      
                                            ?>

                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>

                                        <div class="col-md-6">
                                            <input id="email" type="email" class="form-control <?php echo required_email($_POST['email']) ? '@error(\'name\') is-invalid @enderror': ''; echo email_taken($email_taken) ? '@error(\'name\') is-invalid @enderror': '';?>" name="email" value="<?php echo $_SESSION['email'] ?>">  

                                            <?php 
                                                if  (required_email($_POST['email'])) {
                                                    set_flash($_POST['email'], $message_email);
                                                    echo '<span class="invalid-feedback" role="alert"><strong>';
                                                    get_flash($_POST['email']); 
                                                    echo '</strong></span>';
                                                }
                                                

                                                if (email_taken($email_taken)) {
                                                    set_flash($email_taken, $message_email_taken);
                                                    echo '<span class="invalid-feedback" role="alert"><strong>';
                                                    get_flash($email_taken);
                                                    echo  '</strong></span>';                                   
                                                } 
                                            ?>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                                        <div class="col-md-6">
                                            <input id="password" type="password" class="form-control <?php echo required_pswrd($_POST['password']) ? '@error(\'name\') is-invalid @enderror': ''; echo pswrd_length($_POST['password']) ? '@error(\'name\') is-invalid @enderror': '';?>" name="password" value="<?php echo $_SESSION['password'] ?>" autocomplete="new-password">

                                            <?php 
                                                if (required_pswrd($_POST['password'])) {
                                                    set_flash($_POST['password'], $message_pswrd);
                                                    echo '<span class="invalid-feedback" role="alert"><strong>';
                                                    get_flash($_POST['password']);
                                                    echo '</strong></span>';
                                                }

                                                if (pswrd_length($_POST['password'])) {
                                                    set_flash($_POST['password'], $message_pswrd_length);
                                                    echo '<span class="invalid-feedback" role="alert"><strong>';
                                                    get_flash($_POST['password']);
                                                    echo '</strong></span>';
                                                }
                                            ?>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm Password</label>

                                        <div class="col-md-6">
                                            <input id="password-confirm" type="password" class="form-control <?php echo pswrd_confirm($_POST['password'], $_POST['password_confirmation']) ? '@error(\'name\') is-invalid @enderror': '';?>" name="password_confirmation"  autocomplete="new-password">

                                            <?php
                                            if (pswrd_confirm($_POST['password'], $_POST['password_confirmation'])) {
                                                set_flash($_POST['password'], $message_pswrd_confirm);
                                                echo '<span class="invalid-feedback" role="alert"><strong>';
                                                get_flash($_POST['password']);
                                                echo '</strong></span>';                                                
                                            }
                                            ?>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-0">
                                        <div class="col-md-6 offset-md-4">
                                            <button type="submit" class="btn btn-primary" name="submit">
                                                Register
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>

