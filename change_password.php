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
            
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];
        $password_confirmation = $_POST['password_confirmation'];
        $id = $_SESSION['user_id'];
    
        $errors = [];

        if (empty($old_password)) {
            $errors[] = "Старый пароль не может быть пустым";
        }

        if (empty($new_password)) {
            $errors[] = "Пароль не может быть пустым";
            
        } elseif (strlen($new_password) < 8) {
            $errors[] = "Минимальная длина пароля - 8 символов";
        } elseif (!preg_match("/[0-9]/", $new_password)) {
            $errors[] = "Пароль должен содержать как минимум 1 цифру";
        } elseif (!preg_match("/[a-z]/", $new_password)) {
            $errors[] = "Пароль должен содержать как минимум 1 букву нижнего регистра";
        } elseif (!preg_match("/[A-Z]/", $new_password)) {
            $errors[] = "Пароль должен содержать как минимум 1 букву верхнего регистра";
        }

        if (empty($password_confirmation)) {
            $errors[] = "Повторите пароль";
        }

        if (empty($errors)) {
            
            

            $sql = "SELECT * FROM users WHERE id = '$id'";
            $user = $conn->query($sql);

            $result = $user->fetch_assoc();

            if ($user->num_rows > 0 && !password_verify($old_password, $result['password'])) {
                $errors[] = "Старый пароль введен неверно";
            } else {
                if($new_password != $password_confirmation){
                    $errors[] = "Пароли не совпадают";
                } else {
                    updatePassword($id, password_hash($new_password, PASSWORD_DEFAULT));
                    header("Location: profile.php?password_updated=true");
                }
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
                        <h1>Изменить пароль</h1>
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
                        <label for="email">Эл. почта</label>
                        <input type="email" name="email" id="email" class="form-control" value="<?= $user['email'] ?>" disabled>
                    </div>

                    <div class="mt-2">
                        <label for="old_password">Старый пароль</label>
                        <input type="password" name="old_password" id="old_password" class="form-control">
                    </div>

                    <div class="mt-2">
                        <label for="new_password">Новый пароль</label>
                        <input type="password" name="new_password" id="new_password" class="form-control">
                    </div>

                    <div class="mt-2">
                        <label for="password_confirmation">Повторите пароль</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                    </div>


                    <div class="mt-2">
                        <button type="submit" name="submit" class="btn btn-primary">Сохранить</button>
                    </div>

                </form>
            </div>
        
        </div>

        <?php include_once('./layouts/footer.php') ?>
    
</body>