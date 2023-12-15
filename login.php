<?php 

    include './database/db.php';

    session_start();

    if (isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit;
    }

    include_once('./layouts/head.php');


    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
            
            $email = $_POST['email'];
            $password = $_POST['password'];
        
            $errors = [];
    
            if (empty($email)) {
                $errors[] = "Эл. почта не может быть пустой";
            }
    
            if (empty($password)) {
                $errors[] = "Пароль не может быть пустым";
            }
    
            if (empty($errors)) {
    
                $sql = "SELECT * FROM users WHERE email = '$email'";
                $user = $conn->query($sql);

    
                if ($user->num_rows > 0) {
                    $user = $user->fetch_assoc();
                    if(password_verify($password, $user['password'])){
                        $_SESSION['user_id'] = $user['id'];

                        header("Location: index.php");
                        exit;
                    }else{
                        $errors[] = "Неверный пароль";
                    }
                } else {
                    $errors[] = "Пользователь с такой эл. почтой не существует";
                }
            }
    }
    
?>


<body>

    <?php include_once('./layouts/navbar.php') ?>


    <div class="main-banner col-sm-12 d-flex justify-content-center flex-column align-items-center">

    <?php
        if(!empty($errors) && is_array($errors)){
            foreach($errors as $error){
                echo "<div class='alert alert-danger' role='alert'>
                $error
              </div>";
            }
        
        }
    
    ?>

        <h1>Авторизация</h1>

        <form action="" method="post" style="width: 600px">
            <div class="form-group">
                <label for="exampleInputEmail1">Эл. почта</label>
                <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Введите эл. почту">
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Пароль</label>
                <input type="password" name="password" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Введите пароль">
            </div>  
            <button type="submit" name="submit" class="btn btn-primary">Войти</button>
        </form>

        <div class="mt-4">
        Нет аккаунта?<a href="register.php"> Зарегистрируйтесь</a>
        </div>

    </div>
    
</body>

<?php include_once('./layouts/footer.php') ?>