<?php 

    include_once('./database/db.php');

    include('./layouts/head.php');
    
    session_start();

    if(!$_SESSION['user_id']) {
        header('location: login.php');
    }

    $books = getBooks();
?>
    
    <body>
    
    <?php include('./layouts/navbar.php') ?> 
    


    <!-- ***** Men Area Starts ***** -->
    <section class="section mt-4" id="men">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="section-heading">
                        <h2>Последние книги</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <?php foreach(getBooks() as $book): ?>
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
            </div>
        </div>
    </section>

    <div class="subscribe">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="section-heading">
                        <h2>Подписка</h2>
                        <span>С помощью подписки вы сможете узнать о новых книгах первым.</span>
                    </div>
                    <form id="subscribe" action="" method="get">
                        <div class="row">
                          <div class="col-lg-5">
                            <fieldset>
                              <input name="name" type="text" id="name" placeholder="Your Name" required="">
                            </fieldset>
                          </div>
                          <div class="col-lg-5">
                            <fieldset>
                              <input name="email" type="text" id="email" pattern="[^ @]*@[^ @]*" placeholder="Your Email Address" required="">
                            </fieldset>
                          </div>
                          <div class="col-lg-2">
                            <fieldset>
                              <button type="submit" id="form-submit" class="main-dark-button"><i class="fa fa-paper-plane"></i></button>
                            </fieldset>
                          </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-4">
                    <div class="row">
                        <div class="col-6">
                            <ul>
                                <li>Store Location:<br><span>Jandosova, 55</span></li>
                                <li>Phone:<br><span>010-020-0340</span></li>
                                <li>Office Location:<br><span>Jandosova, 55</span></li>
                            </ul>
                        </div>
                        <div class="col-6">
                            <ul>
                                <li>Work Hours:<br><span>07:30 AM - 9:30 PM Daily</span></li>
                                <li>Email:<br><span>info@company.com</span></li>
                                <li>Social Media:<br><span><a href="#">Facebook</a>, <a href="#">Instagram</a>, <a href="#">Behance</a>, <a href="#">Linkedin</a></span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include_once('./layouts/footer.php') ?>