<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Comments</title>

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
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        <?php 
                            if ($_SESSION['user']) {
                                echo '
                                    <li class="nav-item">
                                    <a class="nav-link" href="logout.php">Logout</a>
                                    </li>';
                            } else {
                                echo '
                                <li class="nav-item">
                                <a class="nav-link" href="login.php">Login</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="register.php">Register</a>
                                </li>';
                            }
                        
                        ?>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3>Комментарии</h3>
                            </div>

                            <?php 
                                if ($_SESSION['success']) {
                                    echo '<div class="card-body">
                                    <div class="alert alert-success" role="alert">';
                                    echo $_SESSION['success'];
                                    echo '</div>';
                                    unset($_SESSION['success']);
                                }                               
                                
                                foreach ($_SESSION['comments'] as $data): ?>
                                

                                    <div class="media">
                                        <img src=" <?php echo $data['user_image']; ?>" class="mr-3" alt="..." width="64" height="64">
                                        <div class="media-body">
                                            <h5 class="mt-0"> <?php echo $data['user_name']; ?> </h5>
                                            <span><small> 
                                                <?php echo date('d/m/Y', strtotime($data['comment_date'])); 
                                               ?>
                                             </small></span>
                                            <p>
                                                <?php echo $data['user_comment']; ?>
                                            </p>
                                        </div>
                                    </div>

                                <?php endforeach; ?>
                                
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12" style="margin-top: 20px;">
                        <div class="card">
                            <div class="card-header">
                                <h3>Оставить комментарий</h3>
                            </div>

                            <?php 
                                if ($_SESSION['message']) {
                                    echo '<div class="card-body">
                                    <div class="alert alert-danger" role="alert">';
                                    echo $_SESSION['message'];
                                    echo '</div>';
                                    unset($_SESSION['message']);
                                }
                            ?>

                            <?php 
                                if ($_SESSION['user']) {
                                    echo '
                                        <div class="card-body">
                                            <form action="index_handler.php" method="post">
                                                <div class="form-group">
                                                    <label for="exampleFormControlTextarea1">Сообщение</label>
                                                    <textarea name="text" class="form-control" id="exampleFormControlTextarea1"
                                                rows="3"></textarea>
                                                </div>
                                                <button type="submit" class="btn btn-success" name="submit">Отправить</button>
                                            </form>
                                        </div>
                                    ';
                                } else {
                                    echo '
                                        <p> Чтобы оставить комментарий, надо авторизироваться</p>
                                        <ul>
                                            <li class="nav-item">
                                                <a class="nav-link" href="login.php">Login</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="register.php">Register</a>
                                            </li> 
                                        </ul>                                   
                                    ';
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>

<!-- <div class="form-group">
<label for="exampleFormControlTextarea1">Имя</label>
<input class="form-control" name="name" value="<?php echo $_SESSION['name']?>" id="exampleFormControlTextarea1" />

</div> -->