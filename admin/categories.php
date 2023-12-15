<?php

    include_once('../database/db.php');

    session_start();

    if (!isset($_SESSION['user_id']) || getUser($_SESSION['user_id'])['role'] != 'admin') {
        header("Location: ../index.php");
        exit;
    }

    include_once('./layouts/head.php');

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteCategory'])){
        $id = $_POST['id'];
        deleteCategory($id);
        header("Location: categories.php?category_deleted=true");
    }


    $alert = "";

    if(isset($_GET['category_added'])){
        $alert = "<div class='alert alert-success'>Категория успешно добавлена</div>";
    }

    if(isset($_GET['category_deleted'])){
        $alert = "<div class='alert alert-danger'>Категория успешно удалена</div>";
    }

?>


<body>
    
        <?php include_once('./layouts/navbar.php') ?>

        <div class="main-banner">

        <?php
            if(isset($alert)){
                echo $alert;
            }
        ?>
        
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <h1>Категории</h1>

                        <div class="mt-2">
                        <a href="add_category.php" class="btn btn-primary">Добавить</a>
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
                                    <th scope="col">Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach(getCategories() as $category): ?>
                                    <tr>
                                        <th scope="row"><?= $category['id'] ?></th>
                                        <td><?= $category['name'] ?></td>
                                        <td style="display:flex; gap:10px">
                                            <a href="edit_user.php?id=<?= $category['id'] ?>" class="btn btn-primary">Редактировать</a>
                                            
                                            <form action="" method="post">
                                                <input type="hidden" name="id" value="<?= $category['id'] ?>">
                                                <input type="submit" name="deleteCategory" class="btn btn-danger" value="Удалить">
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
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