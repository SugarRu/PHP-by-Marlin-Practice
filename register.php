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


   /* Проверка на заполнение поля Name */
   $_SESSION['name_empty'] =  empty($_POST['name']) ? 'Введите имя' : 'valid';

   /* Проверка на заполнение поля E-mail */
   $_SESSION['email_empty'] =  empty($_POST['email']) ? 'Укажите почтовый адрес' : 'valid';

   /* Валидация E-mail */
   $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

   /* Провверка почты на дубликат */
   $STH = $pdo->prepare("SELECT email FROM registration WHERE email = ?");
   $STH->execute([$email]);
   $email_exists = $STH->fetch();

   $_SESSION['email_exists'] = ($email_exists[0] == $_SESSION['email']) ? 'Такая почта уже зарегистрирована' : 'valid';

   /* Проверка на заполнение поля Password */
   $_SESSION['pswrd_empty'] =  empty($_POST['password']) ? 'Создайте пароль' : 'valid';

   /* Проверка пароля на количество символов */
   $_SESSION['pswrd_length'] = strlen($_POST['password']) <= 5 ? 'Пароль не должен быть короче 6 символов' : 'valid';

   /* Проверка на совпадение паролей */
   $_SESSION['password'] = $_POST['password'];
   $_SESSION['password_confirmation'] = $_POST['password_confirmation']; 

   if ($_SESSION['password'] != $_SESSION['password_confirmation'] && $_SESSION['password'] != 0) {       
        $_SESSION['pswrd_ntmchd'] = 'Пароли не совпадают';
        $_SESSION['name'] = $_POST['name'];
        $_SESSION['email'] =  $_POST['email'];
    } else {
        $_SESSION['pswrd_match'] = 'valid'; /* ПОЧЕМУ ВСЕГДА VALID  */
    }    
    
    var_dump($_SESSION['name_empty']);echo'<br>';
    var_dump($_SESSION['email_empty']);echo'<br>';
    var_dump($_SESSION['email_exists']);echo'<br>';
    var_dump($_SESSION['pswrd_empty']);echo'<br>';
    var_dump($_SESSION['pswrd_length']);echo'<br>';
    var_dump($_SESSION['pswrd_ntmchd']);echo'<br>';
    var_dump($_SESSION['pswrd_match']);echo'<br>';
    var_dump($_SESSION['password']);echo'<br>';
    var_dump($_SESSION['password_confirmation']);echo'<br>';
    var_dump($_SESSION['success']);echo'<br>';

    /* Запись в БД */ 
    if ($_SESSION['name_empty'] == $_SESSION['email_empty'] && $_SESSION['email_exists'] == $_SESSION['pswrd_empty'] && $_SESSION['pswrd_length'] == $_SESSION['pswrd_ntmchd'])  {
        
        $sql = "INSERT INTO registration (name, email, password) VALUES (:name, :email, :password)";
        $STH = $pdo->prepare($sql);

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
                                            <input id="name" type="text" class="form-control <?php echo empty($_POST['name']) ? '@error(\'name\') is-invalid @enderror': '';?>" name="name" autofocus value="<?php echo $_SESSION['name']?>"> 

                                            <?php 
                                                if (empty($_POST['name'])) {
                                                    echo '<span class="invalid-feedback" role="alert"><strong>' . $_SESSION['name_empty'] . '</strong></span>';
                                                }                                                
                                            ?>

                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>

                                        <div class="col-md-6">
                                            <input id="email" type="email" class="form-control <?php echo empty($_POST['email']) ? '@error(\'name\') is-invalid @enderror': ''; echo $email_exists[0] == $_SESSION['email'] ? '@error(\'name\') is-invalid @enderror': '';?>" name="email" value="<?php echo $_SESSION['email'] ?>">  

                                            <?php 
                                                if (empty($_POST['email'])) {
                                                    echo '<span class="invalid-feedback" role="alert"><strong>' . $_SESSION['email_empty'] . '</strong></span>';
                                                }

                                                if ($email_exists[0] == $_SESSION['email']) {
                                                    echo '<span class="invalid-feedback" role="alert"><strong>' . $_SESSION['email_exists'] . '</strong></span>';                                   
                                                } 
                                            ?>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                                        <div class="col-md-6">
                                            <input id="password" type="password" class="form-control <?php echo empty($_POST['password']) ? '@error(\'name\') is-invalid @enderror': ''; echo (strlen($_POST['password']) <= 5) ? '@error(\'name\') is-invalid @enderror': '';?>" name="password" value="<?php echo $_SESSION['password'] ?>" autocomplete="new-password">

                                            <?php 
                                                if (empty($_POST['password'])) {
                                                    echo '<span class="invalid-feedback" role="alert"><strong>' . $_SESSION['pswrd_empty'] . '</strong></span>';
                                                }

                                                if (strlen($_POST['password']) <= 5) {
                                                    echo '<span class="invalid-feedback" role="alert"><strong>' . $_SESSION['pswrd_length'] . '</strong></span>';
                                                }
                                            ?>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm Password</label>

                                        <div class="col-md-6">
                                            <input id="password-confirm" type="password" class="form-control <?php echo $_SESSION['password'] != $_SESSION['password_confirmation'] ? '@error(\'name\') is-invalid @enderror': '';?>" name="password_confirmation"  autocomplete="new-password">

                                            <?php
                                            if ($_SESSION['password'] != $_SESSION['password_confirmation']) {
                                                echo '<span class="invalid-feedback" role="alert"><strong>' . $_SESSION['pswrd_ntmchd'] . '</strong></span>';
                                                unset($_SESSION['pswrd_ntmchd']);
                                                unset($_SESSION['password_confirmation']);
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

