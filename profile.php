<?php 

    include_once('./database/db.php');

    session_start();

    include('./layouts/head.php');

    if(!$_SESSION['user_id']) {
        header('location: login.php');
    }

    $user = getUser($_SESSION['user_id']);

    $alert = "";

    if(isset($_GET['user_updated'])){
        $alert = "<div class='alert alert-success'>Профиль успешно обновлен</div>";
    }
?>
    
    <body>
    
    <?php include('./layouts/navbar.php') ?> 
    
    
    <!-- ***** Main Banner Area Start ***** -->
    <div class="main-banner">
        <?php
            if(isset($alert)){
                echo $alert;
            }
        ?>
        <div class="container">
            <h6 style="font-weight: bold">Профиль > <?php echo $user['email'] ?></h6>
            <div class="row mt-4">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-6">
                            <div class="main-banner-text">
                                <div class="mt-2">
                                    <?php if($user['image'] == null): ?>
                                        <img src="./assets/images/profile.png" alt="profile" width="200" height="200">
                                    <?php else: ?>
                                        <img style="border-radius:50%" src="<?php echo $user['image'] ?>" alt="profile" width="200" height="200">
                                    <?php endif; ?>
                                </div>
                                <div class="mt-4">
                                <h2><?php echo $user['firstname'] . ' ' . $user['surname'] ?></h2>
                                </div>

                                <div class="mt-2">
                                    <h6>Почта: <?php echo $user['email'] ?></h6>
                                </div>

                                <div class="mt-2">
                                    <h6>Роль: <?php echo $user['role'] ?></h6>
                                </div>

                                <div class="mt-2">
                                    <a href="change_password.php?id=<?= $user['id'] ?>">Изменить пароль</a>
                                </div>
                                <div class="mt-4">
                                    <a class="btn btn-primary" href="edit_profile.php?id=<?= $_SESSION['user_id'] ?>">Редактировать</a>
                                </div>
                                <div class="blog-footer mt-4">
                                    <ul>
                                        <li><a class="btn btn-danger" href="logout.php">Выйти</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <h3>Сохраненное</h3>

                            <div class="mt-4">
                            <?php
                                $savedBooksDetails = getSavedBooksDetails($_SESSION['user_id']);

                                if ($savedBooksDetails !== false && is_array($savedBooksDetails)) {
                                    foreach ($savedBooksDetails as $book) {
                                        echo '<div class="row">';
                                        echo '<div class="col-4">';
                                        echo '<img src="' . $book['image'] . '" alt="book" width="100" height="100">';
                                        echo '</div>';
                                        echo '<div class="col-8">';
                                        echo '<h6>' . $book['name'] . '</h6>';
                                        echo '<h6>$ ' . $book['price'] . '</h6>';
                                        echo '<a class="btn btn-success mt-4" href="show.php?id=' . $book['id'] . '">Подробнее</a>';
                                        echo '</div>';
                                        echo '</div>';
                                        echo '<hr>';
                                    }
                                } else {
                                    echo '<p>Сохраненных книг не найдено.</p>';
                                }
                                ?>
                        </div>
                    </div>
                </div> 
            </div>

        </div>
    </div>

    <?php include_once('./layouts/footer.php') ?>