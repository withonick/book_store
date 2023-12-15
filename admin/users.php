<?php

    include_once('../database/db.php');

    session_start();

    if (!isset($_SESSION['user_id']) || getUser($_SESSION['user_id'])['role'] != 'admin') {
        header("Location: ../index.php");
        exit;
    }

    include_once('./layouts/head.php');

    $alert = "";

    if(isset($_GET['user_updated'])){
        $alert = "<div class='alert alert-success'>Пользователь успешно обновлен</div>";
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
                        <h1>Управление</h1>
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
                                    <th scope="col">Имя</th>
                                    <th scope="col">Фамилия</th>
                                    <th scope="col">Эл. почта</th>
                                    <th scope="col">Роль</th>
                                    <th scope="col">Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach(getUsers() as $user): ?>
                                    <tr>
                                        <th scope="row"><?= $user['id'] ?></th>
                                        <td><?= $user['firstname'] ?></td>
                                        <td><?= $user['surname'] ?></td>
                                        <td><?= $user['email'] ?></td>
                                        <td><?= $user['role'] ?></td>
                                        <td>
                                            <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn btn-primary">Редактировать</a>
                                            <a href="delete_user.php?id=<?= $user['id'] ?>" class="btn btn-danger">Удалить</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
            </div>
        
        </div>    

        <?php include_once('./layouts/footer.php') ?>

</body>

