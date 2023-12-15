<?php

    include_once('../database/db.php');

    session_start();

    if (!isset($_SESSION['user_id']) || getUser($_SESSION['user_id'])['role'] != 'admin') {
        header("Location: ../index.php");
        exit;
    }

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteBook'])){
        $id = $_POST['id'];
        deleteBook($id);
        header("Location: books.php?book_deleted=true");
    }

    include_once('./layouts/head.php');

    $alert = "";

    if(isset($_GET['book_deleted'])){
        $alert = "<div class='alert alert-success'>Книга успешно удалена</div>";
    }

    
?>


<body>
    
        <?php include_once('./layouts/navbar.php') ?>

        <div class="main-banner">
    
            <div class="container">
            <?php
            if(isset($alert)){
                echo $alert;
            }
        ?>
                <div class="row">
                    <div class="col-sm-12">
                        <h1>Книги</h1>

                        <div class="mt-2">
                        <a href="add_book.php" class="btn btn-primary">Добавить</a>
                    </div>
                    </div>
                </div>
            </div>

            <div class="container mt-4">
                <!-- table -->
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Название</th>
                                    <th scope="col">Автор</th>
                                    <th scope="col">Цена</th>
                                    <th scope="col">Категория</th>
                                    <th scope="col">Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(getBooks()): ?>
                                <?php foreach(getBooks() as $book): ?>
                                    <tr>
                                        <th scope="row"><?= $book['id'] ?></th>
                                        <td><?= $book['name'] ?></td>
                                        <td><?= $book['author'] ?></td>
                                        <td><?= $book['price'] ?></td>
                                        <td><?= getCategory($book['category_id'])['name'] ?></td>
                                        <td style="display:flex; gap:10px">
                                            <a href="edit_book.php?id=<?= $book['id'] ?>" class="btn btn-primary">Редактировать</a>
                                            
                                            <form action="" method="post">
                                                <input type="hidden" name="id" value="<?= $book['id'] ?>">
                                                <input type="submit" name="deleteBook" class="btn btn-danger" value="Удалить">
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3">Книги не найдены</td>
                                    </tr>

                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
            </div>
        
        </div>


        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

        <?php include_once('./layouts/footer.php') ?>
    
</body>