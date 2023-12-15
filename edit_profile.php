<?php

    include_once('./database/db.php');

    session_start();

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    $user = [];

    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $user = getUser($id);
    }

    include_once('./layouts/head.php');


    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
            
        $firstname = $_POST['firstname'];
        $surname = $_POST['surname'];
        $email = $_POST['email'];
        $id = $_SESSION['user_id'];
    
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

        if (empty($errors)) {

            $sql = "SELECT * FROM users WHERE email = '$email'";
            $user = $conn->query($sql);

            $result = $user->fetch_assoc();

            if ($user->num_rows > 0 && $result['id'] != $id) {
                $errors[] = "Пользователь с такой эл. почтой уже существует";
            } else {
                updateProfile($id, $firstname, $surname, $email);
                header("Location: profile.php?user_updated=true");
            }
        }

    }
?>


<body>
    
        <?php include_once('./layouts/navbar.php') ?>

        <div class="main-banner">
        
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <h1>Редактировать профиль</h1>
                    </div>
                </div>
            </div>

            <div class="container mt-4 col-sm-4">

            <?php

                if(!empty($errors) && is_array($errors)){
                    foreach($errors as $error){
                        echo "<div class='alert alert-danger' role='alert'>
                        $error
                      </div>";
                    }
                
                }

            ?>
                
                <form action="" method="post">

                    <div class="mt-2">
                        <label for="firstname">Имя</label>
                        <input type="text" name="firstname" id="firstname" class="form-control" value="<?= $user['firstname'] ?>">
                    </div>

                    <div class="mt-2">
                        <label for="surname">Фамилия</label>
                        <input type="text" name="surname" id="surname" class="form-control" value="<?= $user['surname'] ?>">
                    </div>

                    <div class="mt-2">
                        <label for="email">Эл. почта</label>
                        <input type="email" name="email" id="email" class="form-control" value="<?= $user['email'] ?>">
                    </div>

                    <div class="mt-2">
                        <button type="submit" name="submit" class="btn btn-primary">Сохранить</button>
                    </div>

                </form>
            </div>
        
        </div>

        <?php include_once('./layouts/footer.php') ?>
    
</body>