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

    $_SESSION['password'] = $_POST['password'];
    $_SESSION['password_confirmation'] = $_POST['password_confirmation'];

    if ($_SESSION['password'] == $_SESSION['password_confirmation'] && $_SESSION['password'] != 0) {

        $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
        $pdo = new PDO('mysql:host=localhost;dbname=marlin_php', 'root', '', $options);

        $sql = "INSERT INTO registration (name, email, password) VALUES (:name, :email, :password)";
        
        $STH = $pdo->prepare($sql);

        $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);



        $STH->execute(array (
            'name' => $_POST['name'],
            'email' => $email,
            'password' => $hash
        ));  

        $_SESSION['success'] = 'Успешная регистрация';

    } else {

        $_SESSION['error_pass'] = 'Пароли не совпадают';
        $_SESSION['name'] = $_POST['name'];
        $_SESSION['email'] =  $_POST['email'];
        
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
                                <a class="nav-link" href="login.html">Login</a>
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
                                unset($_SESSION['name']);
                                unset($_SESSION['success']);
                                unset($_SESSION['email']);
                                unset($_SESSION['password']);
                                unset($_SESSION['password_confirmation']);
                                
                            }                                          

                            ?>

                            <div class="card-body">
                                <form method="POST" action="">

                                    <div class="form-group row">
                                        <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>

                                        <div class="col-md-6">
                                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" autofocus value="<?php echo $_SESSION['name']?>">

                                            <?php 
                                                if (empty($_POST['name'])) {
                                                    echo '<span class="invalid-feedback" role="alert"><strong>' . "Введите имя" . '</strong></span>';
                                                }                                                
                                            ?>

                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>

                                        <div class="col-md-6">
                                            <input id="email" type="email" class="form-control" name="email" value="<?php echo $_SESSION['email'] ?>">

                                            <?php 
                                                if (empty($_POST['email'])) {
                                                    echo '<span class="invalid-feedback" role="alert"><strong>' . "Введите e-mail" . '</strong></span>';
                                                }
                                            ?>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                                        <div class="col-md-6">
                                            <input id="password" type="password" class="form-control " name="password" value="<?php echo $_SESSION['password'] ?>" autocomplete="new-password">

                                            <?php 
                                                if (empty($_POST['password'])) {
                                                    echo '<span class="invalid-feedback" role="alert"><strong>' . "Введите пароль" . '</strong></span>';
                                                }
                                            ?>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm Password</label>

                                        <div class="col-md-6">
                                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation"  autocomplete="new-password">

                                            <?php
                                            if ($_SESSION['error_pass'] && $_SESSION['password'] != 0) {
                                                echo '<span class="invalid-feedback" role="alert"><strong>' . $_SESSION['error_pass'] . '</strong></span>';
                                                unset($_SESSION['error_pass']);
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

<!--  <?php 

if ($_SESSION['error_pass'] && $_SESSION['password'] != 0) {
    echo '<span class="invalid-feedback" role="alert"><strong>' . $_SESSION['error_pass'] . '</strong></span>';
    unset($_SESSION['error_pass']);
    unset($_SESSION['password_confirmation']);
}
?>  -->