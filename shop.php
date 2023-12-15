<?php 

    include_once('./database/db.php');

    session_start();

    if(!$_SESSION['user_id']) {
        header('location: login.php');
    }

    include('./layouts/head.php');

    if(isset($_GET['bookId'])){
        $bookId = $_GET['bookId'];
        saveBook($_SESSION['user_id'], $bookId);

        header("Location: shop.php?book_saved=true");
    }

    if(isset($_GET['category'])){
        $category = $_GET['category'];
        $books = getBooksByCategory($category);
        $title = getCategory($category)['name'];
    }
    else if(isset($_GET['search'])){
        $search = $_GET['search'];
        $books = searchBooks($search);
        $title = "Результаты поиска";
    }
    else {
        $books = getBooks();
        $title = "Все книги";
    }

    $alert = "";

    if(isset($_GET['book_saved'])){
        $alert = "<div class='alert alert-success'>Книга успешно добавлена в избранное</div>";
    }
   
    if(isset($_GET['book_unsaved'])){
        $alert = "<div class='alert alert-danger'>Книга успешно удалена из избранного</div>";
    }


?>
    
    <body>
    
    <?php include('./layouts/navbar.php') ?> 

    <div class="page-heading" style="background:none" id="top">

    <?php
        if(isset($alert)){
            echo $alert;
        }
    ?>
        <div class="search-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <form action="" method="GET" class="d-flex">
                        <input type="text" class="form-control" placeholder="Найдите книгу по названию или автору" name="search">
                        <button type="submit" class="btn bg-secondary text-white" style="width: 100px;"><i class="fa fa-search"></i></button>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </div>
    
    <section>
        <div class="container">
        <section class="section" id="products">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-heading">
                        <h2><?= $title ?></h2>
                        <span>Список всех книг.</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <?php if(!empty($books)): ?>
                    <?php foreach($books as $book): ?>
                    <div class="col-lg-4">
                    <div class="item">
                        <div class="thumb">
                            <div class="hover-content">
                                <ul>
                                    <li><a href="show.php?id=<?php echo $book['id'] ?>"><i class="fa fa-eye"></i></a></li>
                                    <?php if(isset($_SESSION['user_id'])): ?>
                                    <li><a href="shop.php?bookId=<?php echo $book['id'] ?>"><i class="fa fa-star" <?php if(getSavedBook($_SESSION['user_id'], $book['id'])): ?> style="color:gold" <?php endif; ?>></i></a></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                            <img style="object-fit:contain; width:100%" src="<?php echo $book['image'] ?>" alt="">
                        </div>
                        <div class="down-content">
                            <h4><?php echo substr($book['name'], 0, 37); ?></h4>
                            <span>$ <?php echo $book['price'] ?></span>
                            <ul class="stars m-4" data-book-id="1">
                        <?php
                        $averageRating = getAverageRating($book['id']);
                        for ($i = 1; $i <= 5; $i++) {
                            $selected = ($i <= $averageRating) ? 'selected' : '';
                            echo '<li class="star ' . $selected . '" data-rating="' . $i . '"><i class="fa fa-star"></i></li>';
                        }
                        ?>
                    </ul>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-lg-12">
                        <div class="section-heading">
                            <h3>Книги не найдены.</h3>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
        </div>
    </section>

    
    <?php include_once('./layouts/footer.php') ?>