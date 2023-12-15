<?php 

    include './database/db.php';

    session_start();

    if (isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit;
    }

    include_once('./layouts/head.php');


    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
        $firstname = $_POST['firstname'];
        $surname = $_POST['surname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $password_confirmation = $_POST['password_confirmation'];
        $image = $_FILES['image']['name'];
    
        $errors = [];
    
        if (empty($firstname)) {
            $errors[] = "Имя не может быть пустым";
        }
    
        if (empty($surname)) {
            $errors[] = "Фамилия не может быть пустой";
        }
    
        if (empty($email)) {
            $errors[] = "Эл. почта не может быть пустой";
        }
    
        if (empty($password)) {
            $errors[] = "Пароль не может быть пустым";
        } elseif (strlen($password) < 8) {
            $errors[] = "Минимальная длина пароля - 8 символов";
        } elseif (!preg_match("/[0-9]/", $password)) {
            $errors[] = "Пароль должен содержать как минимум 1 цифру";
        } elseif (!preg_match("/[a-z]/", $password)) {
            $errors[] = "Пароль должен содержать как минимум 1 букву нижнего регистра";
        } elseif (!preg_match("/[A-Z]/", $password)) {
            $errors[] = "Пароль должен содержать как минимум 1 букву верхнего регистра";
        }
    
        if (empty($password_confirmation)) {
            $errors[] = "Повторите пароль";
        }
    
        if ($password != $password_confirmation) {
            $errors[] = "Пароли не совпадают";
        }
    
        if (empty($errors)) {
            $sql = "SELECT * FROM users WHERE email = '$email'";
            $user = $conn->query($sql);
        
            if ($user->num_rows > 0) {
                $errors[] = "Пользователь с такой эл. почтой уже существует";
            } else {
                $uploadDir = './uploads/';
                $uploadFile = $uploadDir . basename($image);
        
                // Corrected file upload handling
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                    registerUser($firstname, $surname, $email, password_hash($password, PASSWORD_DEFAULT), $image, 'user');
                    header("Location: login.php");
                    exit;
                } else {
                    $errors[] = "Ошибка при загрузке изображения";
                }
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
        <h1>Регистрация</h1>

        <form action="" method="post" style="width: 600px" enctype="multipart/form-data">
            <div class="form-group">
                <label for="exampleInputEmail1">Имя</label>
                <input type="text" name="firstname" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Введите имя">
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Фамилия</label>
                <input type="text" name="surname" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Введите фамилию">
            </div>
            <div class="form-group">
                <label for="image">Изображение</label>
                <input type="file" name="image" id="image" class="form-control">
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Эл. почта</label>
                <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Введите эл. почту">
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Пароль</label>
                <input type="password" name="password" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Введите пароль">
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Повторите пароль</label>
                <input type="password" name="password_confirmation" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Повторите пароль">
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Зарегистрироваться</button>
        </form>
        <div class="mt-4">
            <span>Уже есть аккаунт? <a href="login.php">Войти</a></span>
        </div>

    </div>
    
</body>

<?php include_once('./layouts/footer.php') ?>