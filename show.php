<?php 

    include_once('./database/db.php');

    include('./layouts/head.php');
    
    session_start();

    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $book = getBook($id);
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])){
        $book_id = $_POST['book_id'];
        $user_id = $_POST['user_id'];
        $amount = $_POST['amount'];

        $errors = [];

        if(empty($amount)){
            $errors[] = "Количество не может быть пустым";
        }

        if(empty($errors)){
            $price = $book['price'];
            $total = $price * $amount;

            addToCart($user_id, $book_id, $amount, $total);
            header("Location: cart.php?added_to_cart=true");
        }
    }

    $alert = "";

    if(isset($_GET['rating_added'])){
        $alert = "<div class='alert alert-success'>Ваша оценка успешно добавлена</div>";
    }

?>
    
    <body>
    
    <?php include('./layouts/navbar.php') ?> 

    <!-- ***** Main Banner Area Start ***** -->
    <div class="page-heading" id="top">
    
        <?php
        
            if(isset($alert)){
                echo $alert;
            }

        ?>

    </div>
    <!-- ***** Main Banner Area End ***** -->


    <!-- ***** Product Area Starts ***** -->
    <section class="section" id="product">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                <div class="left-images">
                    <img src="<?php echo $book['image'] ?>" alt="">
                </div>
            </div>
            <div class="col-lg-4">
                <div class="right-content">
                    <h4><?php echo $book['name'] ?></h4>
                    
                    <span class="price">$<?php echo $book['price'] ?></span>
                    

                    <ul class="stars m-4" data-book-id="1">
                        <?php
                        $averageRating = getAverageRating($book['id']);
                        for ($i = 1; $i <= 5; $i++) {
                            $selected = ($i <= $averageRating) ? 'selected' : '';
                            echo '<li class="star ' . $selected . '" data-rating="' . $i . '"><i class="fa fa-star"></i></li>';
                        }
                        ?>
                    </ul>
                    
                    <span><?php echo $book['author'] ?></span>
                    <div class="quote">
                        <i class="fa fa-quote-left"></i><p><?php echo $book['description'] ?></p>
                    </div>
                    <form action="" method="post">

                    <input type="hidden" name="book_id" value="<?php echo $book['id'] ?>">
                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id'] ?>">
                    
                    <div class="quantity-content">
                        <div class="left-content">
                            <h6>Количество книг</h6>
                        </div>
                        <div class="right-content">
                        <div class="quantity buttons_added">
                            <input type="button" value="-" class="minus">
                            <input type="number" step="1" min="1" max="" name="amount" value="1" title="Qty" class="input-text qty text">
                            <input type="button" value="+" class="plus">
                        </div>
                        </div>
                    </div>
                    <div class="total">
                        <h4>Общая Сумма: $<span name="total" id="totalAmount"><?php echo $book['price'] ?>.00</span></h4>
                        <div class="main-border-button"><button name="add_to_cart">Добавить в корзину</button></div>
                    </div>
                    </form>


                    <script>
                    document.addEventListener('DOMContentLoaded', function() {

                        var qtyInput = document.querySelector('.qty');
                        var totalAmount = document.getElementById('totalAmount');

                        var pricePerBook = <?php echo $book['price'] ?>;
                        var total = pricePerBook;

                        function updateTotal() {
                            var quantity = parseInt(qtyInput.value, 10) || 1;
                            total = pricePerBook * quantity;
                            totalAmount.textContent = total.toFixed(2);
                        }

                        document.querySelector('.plus').addEventListener('click', function() {
                            qtyInput.stepUp();
                            updateTotal();
                        });

                        document.querySelector('.minus').addEventListener('click', function() {
                            qtyInput.stepDown();
                            updateTotal();
                        });

                        qtyInput.addEventListener('input', updateTotal);
                    });
</script>

                </div>
            </div>
            </div>
        </div>
    </section>


    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>


    <script>
        $(document).ready(function() {
            $('.stars .star').on('click', function() {
                var bookId = <?php echo $book['id'] ?>;
                var rating = $(this).data('rating');

                // Отправляем оценку на сервер (например, через AJAX)
                $.ajax({
                    type: 'POST',
                    url: 'rate.php', // Замените на путь к вашему PHP-обработчику
                    data: {
                        book_id: bookId,
                        rating: rating
                    },
                    success: function(response) {
                        // Обработка успешного ответа, если необходимо
                        console.log(response);
                    },
                    error: function(error) {
                        // Обработка ошибки, если необходимо
                        console.error(error);
                    }
                });

                // Обновляем визуальное отображение рейтинга
                $(this).parent().find('.star').removeClass('selected');
                $(this).prevAll().andSelf().addClass('selected');
            });
        });
    </script>

    <!-- ***** Product Area Ends ***** -->

    
    <?php include_once('./layouts/footer.php') ?>