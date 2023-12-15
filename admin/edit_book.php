<?php

    include_once('../database/db.php');

    session_start();

    if (!isset($_SESSION['user_id']) || getUser($_SESSION['user_id'])['role'] != 'admin') {
        header("Location: ../index.php");
        exit;
    }

    $book = [];

    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $book = getBook($id);
    }

    include_once('./layouts/head.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
        $name = $_POST['name'];
        $author = $_POST['author'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $category_id = $_POST['role'];
        $image = $_FILES['image']['name'];
    
        $oldImage = $book['image'];
    
        $errors = [];
    
        if (empty($name)) {
            $errors[] = "Название не может быть пустым";
        }
    
        if (empty($author)) {
            $errors[] = "Автор не может быть пустым";
        }
    
        if (empty($description)) {
            $errors[] = "Описание не может быть пустым";
        }
    
        if (empty($price)) {
            $errors[] = "Цена не может быть пустой";
        }
    
        if (empty($category_id)) {
            $errors[] = "Категория не может быть пустой";
        }
    
        if (empty($errors)) {
            // Проверяем, было ли выбрано новое изображение
            if (!empty($image)) {
                $uploadDir = '../uploads/';
                $uploadFile = $uploadDir . basename($image);
    
                // Удаляем старое изображение
                if (!empty($oldImage) && file_exists($uploadDir . $oldImage)) {
                    unlink($uploadDir . $oldImage);
                }
    
                // Перемещаем новое изображение
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                    updateBook($id, $name, $author, $description, $price, $category_id, $image);
                    header("Location: books.php?book_updated=true");
                } else {
                    $errors[] = "Ошибка при загрузке изображения";
                }
            } else {
                // Если новое изображение не выбрано, просто обновляем остальные поля
                updateBook($id, $name, $author, $description, $price, $category_id, $oldImage);
                header("Location: books.php?book_updated=true");
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
                        <h1>Редактировать книгу</h1>
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
                
                <form action="" method="post" enctype="multipart/form-data">

                    <div class="mt-2">
                        <label for="name">Название</label>
                        <input type="text" name="name" id="name" class="form-control" value="<?= $book['name'] ?>">
                    </div>

                    <div class="mt-2">
                        <label for="author">Автор</label>
                        <input type="text" name="author" id="author" class="form-control" value="<?= $book['author'] ?>">
                    </div>

                    <div class="mt-2">
                        <label for="description">Описание</label>
                        <textarea name="description" class="form-control" cols="30" rows="10"><?= $book['description'] ?></textarea>
                    </div>

                    <div class="mt-2">
                        <label for="price">Цена</label>
                        <input type="number" name="price" id="price" class="form-control" value="<?= $book['price'] ?>">
                    </div>

                    <div class="mt-2">
                        <label for="role">Категория</label>
                        <select name="role" id="role" class="form-control">
                            <?php foreach(getCategories() as $category): ?>
                                <option value="<?= $category['id'] ?>" <?= $category['id'] == $book['category_id'] ? 'selected' : '' ?>><?= $category['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mt-2">
                        <label for="image">Изображение</label>
                        <input type="file" name="image" id="image" class="form-control">
                        <small>Текущий файл: <?= $book['image'] ?></small>
                    </div>

                    <div class="mt-2">
                        <button type="submit" name="submit" class="btn btn-primary">Сохранить</button>
                    </div>

                </form>
            </div>
        
        </div>

        <?php include_once('./layouts/footer.php') ?>
    
</body>