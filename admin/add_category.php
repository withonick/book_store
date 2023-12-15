<?php

    include_once('../database/db.php');

    session_start();

    if (!isset($_SESSION['user_id']) || getUser($_SESSION['user_id'])['role'] != 'admin') {
        header("Location: ../index.php");
        exit;
    }

    include_once('./layouts/head.php');


    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
            
            $name = $_POST['name'];
        
            $errors = [];
    
            if (empty($name)) {
                $errors[] = "Название не может быть пустым";
            }
    
            if (empty($errors)) {
    
                $sql = "SELECT * FROM categories WHERE name = '$name'";
                $category = $conn->query($sql);
            }

            if ($category->num_rows > 0) {
                $errors[] = "Категория с таким названием уже существует";
            } else {
                storeCategory($name);
                header("Location: categories.php?category_added=true");
            }

        }
?>


<body>
    
        <?php include_once('./layouts/navbar.php') ?>

        <div class="main-banner">
        
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <h1>Добавить категорию</h1>
                    </div>
                </div>
            </div>

            <div class="container mt-4 col-sm-4">
                
                <form action="" method="post">

                    <div class="mt-2">
                        <label for="name">Название</label>
                        <input type="text" name="name" id="name" class="form-control">

                        <button type="submit" name="submit" class="btn btn-primary mt-2">Добавить</button>
                    </div>

                </form>
            </div>
        
        </div>

        <?php include_once('./layouts/footer.php') ?>
    
</body>