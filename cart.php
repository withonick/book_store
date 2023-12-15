<?php 

    include_once('./database/db.php');

    include('./layouts/head.php');
    
    session_start();

    if(!$_SESSION['user_id']) {
        header('location: login.php');
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])){
        $amount = $_POST['amount'];
        $id = $_POST['cart_id'];
        $bookPrice = $_POST['price'];

        $errors = [];

        if(empty($errors)){
            $price = $bookPrice;
            $total = $price * $amount;

            updateCartItem($id, $amount, $total);
            header("Location: cart.php?cart_updated=true");
        }
    }

    $alert = "";

    if($_GET['added_to_cart']){
        $alert = "<div class='alert alert-success'>Книга успешно добавлена в корзину</div>";
    }

    if($_GET['cart_updated']){
        $alert = "<div class='alert alert-success'>Корзина успешно обновлена</div>";
    }

    $cartBooks = getCartItems($_SESSION['user_id']);
?>

    <style>
        .title{
            margin-bottom: 5vh;
        }
        .card{
            margin: auto;
            max-width: 950px;
            width: 90%;
            box-shadow: 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            border-radius: 1rem;
            border: transparent;
        }
        @media(max-width:767px){
            .card{
                margin: 3vh auto;
            }
        }
        .cart{
            background-color: #fff;
            padding: 4vh 5vh;
            border-bottom-left-radius: 1rem;
            border-top-left-radius: 1rem;
        }
        @media(max-width:767px){
            .cart{
                padding: 4vh;
                border-bottom-left-radius: unset;
                border-top-right-radius: 1rem;
            }
        }
        .summary{
            background-color: #ddd;
            border-top-right-radius: 1rem;
            border-bottom-right-radius: 1rem;
            padding: 4vh;
            color: rgb(65, 65, 65);
        }
        @media(max-width:767px){
            .summary{
            border-top-right-radius: unset;
            border-bottom-left-radius: 1rem;
            }
        }
        .summary .col-2{
            padding: 0;
        }
        .summary .col-10
        {
            padding: 0;
        }.row{
            margin: 0;
        }
        .title b{
            font-size: 1.5rem;
        }
        .main{
            margin: 0;
            padding: 2vh 0;
            width: 100%;
        }
        .col-2, .col{
            padding: 0 1vh;
        }
        a{
            padding: 0 1vh;
        }
        .close{
            margin-left: auto;
            font-size: 0.7rem;
        }
        img{
            width: 3.5rem;
        }
        .back-to-shop{
            margin-top: 4.5rem;
        }
        h5{
            margin-top: 4vh;
        }
        hr{
            margin-top: 1.25rem;
        }
        form{
            padding: 2vh 0;
        }
        select{
            border: 1px solid rgba(0, 0, 0, 0.137);
            padding: 1.5vh 1vh;
            margin-bottom: 4vh;
            outline: none;
            width: 100%;
            background-color: rgb(247, 247, 247);
        }
        input{
            border: 1px solid rgba(0, 0, 0, 0.137);
            padding: 1vh;
            margin-bottom: 4vh;
            outline: none;
            width: 100%;
            background-color: rgb(247, 247, 247);
        }
        input:focus::-webkit-input-placeholder
        {
            color:transparent;
        }
        .btn{
            background-color: #000;
            border-color: #000;
            color: white;
            width: 100%;
            font-size: 0.7rem;
            margin-top: 4vh;
            padding: 1vh;
            border-radius: 0;
        }
        .btn:focus{
            box-shadow: none;
            outline: none;
            box-shadow: none;
            color: white;
            -webkit-box-shadow: none;
            -webkit-user-select: none;
            transition: none; 
        }
        .btn:hover{
            color: white;
        }
        a{
            color: black; 
        }
        a:hover{
            color: black;
            text-decoration: none;
        }
        #code{
            background-image: linear-gradient(to left, rgba(255, 255, 255, 0.253) , rgba(255, 255, 255, 0.185)), url("https://img.icons8.com/small/16/000000/long-arrow-right.png");
            background-repeat: no-repeat;
            background-position-x: 95%;
            background-position-y: center;
        }
    </style>
    
    <body>
    
    <?php include('./layouts/navbar.php') ?> 
    


    <!-- ***** Men Area Starts ***** -->
    <section class="section mt-4" id="men">
       
        <?php 
            if(isset($alert)){
                echo $alert;
            }

            if(!empty($errors) && is_array($errors)){
                foreach($errors as $error){
                    echo "<div class='alert alert-danger' role='alert'>
                    $error
                  </div>";
                }
            
            }
        ?>

        <div class="container">
        <div class="card">
            <div class="row">
                <div class="col-md-8 cart">
                    <div class="title">
                        <div class="row">
                            <div class="col"><h4><b>Корзина</b></h4></div>
                            <?php if(!empty($cartBooks) && is_array($cartBooks)): ?>
                            <div class="col align-self-center text-right text-muted">Всего: <?= count($cartBooks) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>    
                    
                    <?php if(!empty($cartBooks) && is_array($cartBooks)): ?>
                        <?php foreach($cartBooks as $book): ?>
                            <div class="row border-top border-bottom">
                                <form action="cart.php" method="post" class="row main align-items-center">
                                    <input type="hidden" name="cart_id" value="<?= $book['cart_id'] ?>">
                                    <input type="hidden" name="price" value="<?= $book['price'] ?>">
                                    <div class="col-2"><img class="img-fluid" src="<?= $book['image'] ?>"></div>
                                    <div class="col">
                                        <div class="row text-muted"><?= getCategory($book['category_id'])['name'] ?></div>
                                        <div class="row"><?= $book['name'] ?></div>
                                    </div>
                                    <div class="col">
                                        <div class="quantity buttons_added">
                                            <input type="button" value="-" class="minus">
                                            <input type="number" step="1" min="0" max="" name="amount" value="<?= $book['amount'] ?>" title="Qty" class="input-text qty text" data-price="<?= $book['price'] ?>">
                                            <input type="button" value="+" class="plus">
                                        </div>
                                    </div>
                                    <div class="col">
                                        $ <?= $book['price'] ?>
                                        <span class="close"><button style="background-color:green; border:none; margin-top: 2px; padding: 5px; color: white; border-radius: 5px;" name="update_cart">Обновить</button></span>
                                    </div>
                                </form>
                            </div>
                        <?php endforeach; ?>

                    <?php else: ?>
                        <span>
                            <h5>Ваша корзина пуста.</h5>
                        </span>
                    <?php endif; ?>
                    
                    <div class="back-to-shop"><a href="shop.php">&leftarrow;</a><span class="text-muted">Назад к покупкам</span></div>
                </div>
                <div class="col-md-4 summary">
                    <div class="mb-2"><h5><b>Summary</b></h5></div>
                    <div class="row" style="border-top: 1px solid rgba(0,0,0,.1); padding: 2vh 0;">
                        <div class="col">Общая Сумма</div>
                        <div class="col text-right" id="totalAmount">$ <?= getTotalPrice($_SESSION['user_id']) ?>.00</div>
                    </div>
                    <!-- <button class="btn">К покупке</button> -->
                </div>
            </div>
            
        </div>
        </div>
        
    </section>


    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                    var qtyInputs = document.querySelectorAll('.qty');
                    var totalAmount = document.getElementById('totalAmount');

                    function updateTotal() {
                        var totalPrice = 0;

                        qtyInputs.forEach(function(qtyInput) {
                            var pricePerBook = parseFloat(qtyInput.dataset.price);
                            var quantity = parseInt(qtyInput.value, 10) || 1;
                            totalPrice += pricePerBook * quantity;
                        });

                        totalAmount.textContent = '$ ' + totalPrice.toFixed(2);
                    }

                    function handleQuantityChange() {
                        updateTotal();
                    }

                    function handleButtonClick(event) {
                    var qtyInput = event.target.parentNode.querySelector('.qty');
                    var currentQuantity = parseInt(qtyInput.value, 10) || 1;

                    if (event.target.classList.contains('plus')) {
                        qtyInput.value = currentQuantity + 1;
                    } else if (event.target.classList.contains('minus')) {
                        // Check for minimum value of 0
                        qtyInput.value = Math.max(currentQuantity - 1, 0);
                    }

                    updateTotal();
                    }

                    qtyInputs.forEach(function(qtyInput) {
                        qtyInput.addEventListener('input', handleQuantityChange);
                    });

                    var plusButtons = document.querySelectorAll('.plus');
                    var minusButtons = document.querySelectorAll('.minus');

                    plusButtons.forEach(function(button) {
                        button.addEventListener('click', handleButtonClick);
                    });

                    minusButtons.forEach(function(button) {
                        button.addEventListener('click', handleButtonClick);
                    });
                });


</script>

    
    <?php include_once('./layouts/footer.php') ?>