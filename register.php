<?php session_start();?>

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

                                    unset(
                                        $_POST['name'],
                                        $_POST['password'],
                                        $_POST['password'],
                                        $_POST['password_confirmation'],
                                        $_SESSION['success'],
                                        $_SESSION['name'],
                                        $_SESSION['email'],
                                        $_SESSION['pswrd']
                                    );
                                }
                            ?>
                            

                            <div class="card-body">
                                <form method="POST" action="registration_handler.php">

                                    <div class="form-group row">
                                        <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>

                                        <div class="col-md-6">
                                            <input id="name" type="text" class="form-control <?php echo $_SESSION['message_name'] ? '@error(\'name\') is-invalid @enderror': '';?>" name="name" value="<?php echo $_SESSION['name']?>"> 
                                             
                                            <?php 
                                                if ($_SESSION['message_name']) {
                                                    echo '<span class="invalid-feedback" role="alert"><strong>'; 
                                                    echo $_SESSION['message_name']; 
                                                    echo '</strong></span>';
                                                    unset($_SESSION['message_name']);
      
                                                }            
                                            ?>

                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>

                                        <div class="col-md-6">
                                            <input id="email" type="email" class="form-control <?php echo $_SESSION['message_email'] || $_SESSION['message_email_taken'] ? '@error(\'name\') is-invalid @enderror': '';?>" name="email" value="<?php echo $_SESSION['email']?>">  

                                            <?php 
                                                if ($_SESSION['message_email']) {
                                                    echo '<span class="invalid-feedback" role="alert"><strong>';
                                                    echo $_SESSION['message_email']; 
                                                    echo '</strong></span>';
                                                    unset($_SESSION['message_email']);
                                                }                                                

                                                if ($_SESSION['message_email_taken']) {
                                                    echo '<span class="invalid-feedback" role="alert"><strong>';
                                                    echo $_SESSION['message_email_taken']; 
                                                    echo '</strong></span>';
                                                    unset($_SESSION['message_email_taken']);
                                                }
                                            ?>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                                        <div class="col-md-6">
                                            <input id="password" type="password" class="form-control 
                                            <?php echo $_SESSION['message_pswrd'] ? '@error(\'name\') is-invalid @enderror': '';?>
                                            <?php echo $_SESSION['message_pswrd_length'] ? '@error(\'name\') is-invalid @enderror': '';?>" name="password" value="<?php echo $_SESSION['pswrd']?>" autocomplete="new-password">

                                            <?php
                                                if ($_SESSION['message_pswrd']) {
                                                    echo '<span class="invalid-feedback" role="alert"><strong>';
                                                    echo $_SESSION['message_pswrd']; 
                                                    echo '</strong></span>';
                                                    unset($_SESSION['message_pswrd']);
                                                }

                                                if ($_SESSION['message_pswrd_length']) {
                                                    echo '<span class="invalid-feedback" role="alert"><strong>';
                                                    echo $_SESSION['message_pswrd_length']; 
                                                    echo '</strong></span>';
                                                    unset($_SESSION['message_pswrd_length']);
                                                }
                                            ?>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm Password</label>

                                        <div class="col-md-6">
                                            <input id="password-confirm" type="password" class="form-control <?php echo $_SESSION['message_pswrd_empty'] || $_SESSION['message_pswrd_confirm'] ? '@error(\'name\') is-invalid @enderror': '';?>" name="password_confirmation"  autocomplete="new-password">

                                            <?php 

                                                if ($_SESSION['message_pswrd_empty']) {
                                                    echo '<span class="invalid-feedback" role="alert"><strong>';
                                                    echo $_SESSION['message_pswrd_empty']; 
                                                    echo '</strong></span>';
                                                    unset($_SESSION['message_pswrd_empty']);
                                                }

                                                if ($_SESSION['message_pswrd_confirm']) {
                                                    echo '<span class="invalid-feedback" role="alert"><strong>';
                                                    echo $_SESSION['message_pswrd_confirm']; 
                                                    echo '</strong></span>';
                                                    unset($_SESSION['message_pswrd_confirm']);
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

