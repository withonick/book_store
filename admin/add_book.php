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
    $author = $_POST['author'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $image = $_FILES['image'];
    $category_id = $_POST['category_id'];

    $errors = [];

    if (empty($name)) {
        $errors[] = "Название не может быть пустым";
    }

    if (empty($author)) {
        $errors[] = "Автор не может быть пустым";
    }

    if (empty($price)) {
        $errors[] = "Цена не может быть пустой";
    }

    if (empty($description)) {
        $errors[] = "Описание не может быть пустым";
    }

    if (empty($image['name'])) {
        $errors[] = "Изображение не может быть пустым";
    }

    if (empty($category_id)) {
        $errors[] = "Категория не может быть пустой";
    }

    if (empty($errors)) {

        $sql = "SELECT * FROM books WHERE name = '$name'";
        $book = $conn->query($sql);

        if ($book->num_rows > 0) {
            $errors[] = "Книга с таким названием уже существует";
        } else {

            $uploadDir = '../uploads/';
            $uploadFile = $uploadDir . basename($image['name']);


            if (move_uploaded_file($image['tmp_name'], $uploadFile)) {
                storeBook($name, $author, $price, $description, $image['name'], $_SESSION['user_id'], $category_id);
                header("Location: books.php?book_added=true");
            } else {
                $errors[] = "Ошибка при загрузке изображения";
            }

        }
    }
}
?>


<body>
    
        <?php include_once('./layouts/navbar.php') ?>

        <div class="main-banner">

        <?php
        if(!empty($errors) && is_array($errors)){
            foreach($errors as $error){
                echo "<div class='alert alert-danger' role='alert'>
                $error
              </div>";
            }
        
        }
    
    ?>
        
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <h1>Добавить книгу</h1>
                    </div>
                </div>
            </div>

            <div class="container mt-4 col-sm-4">
                
                <form action="" method="post" enctype="multipart/form-data">

                    <div class="mt-2">
                        <label for="name">Название</label>
                        <input type="text" name="name" id="name" class="form-control">
                    </div>

                    <div class="mt-2">
                        <label for="author">Автор</label>
                        <input type="text" name="author" id="author" class="form-control">
                    </div>

                    <div class="mt-2">
                        <label for="price">Цена</label>
                        <input type="number" name="price" id="price" class="form-control">
                    </div>

                    <div class="mt-2">
                        <label for="category">Категория</label>

                        <select name="category_id" class="form-control">
                            <?php foreach(getCategories() as $categories): ?>
                                <option value="<?= $categories['id'] ?>"><?= $categories['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mt-2">
                        <label for="description">Описание</label>
                        <textarea name="description" id="description" cols="30" rows="10" class="form-control"></textarea>
                    </div>

                    <div class="mt-2">
                        <label for="image">Изображение</label>
                        <input type="file" name="image" id="image" class="form-control">
                    </div>

                    <div class="mt-2">
                        <button class="btn btn-primary" name="submit">Добавить</button>
                    </div>

                </form>
            </div>
        
        </div>

        <?php include_once('./layouts/footer.php') ?>
    
</body>