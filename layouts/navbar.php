<?php 

include_once('./database/db.php');

$categories = getCategories();

?>



<header class="header-area header-sticky">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="main-nav">
                        <!-- ***** Logo Start ***** -->
                        <a href="index.php" class="logo text-dark">
                        Papyrus
                        </a>
                        <!-- ***** Logo End ***** -->
                        <!-- ***** Menu Start ***** -->
                        <ul class="nav">
                            <li class="scroll-to-section"><a href="index.php">Главная</a></li>
                            <li class="submenu">
                                <a href="javascript:;">Категории</a>
                                <ul>
                                    <li><a href="shop.php">Все книги</a></li>
                                    
                                    <?php foreach($categories as $category): ?>
                                        <li><a href="shop.php?category=<?php echo $category['id'] ?>"><?php echo $category['name'] ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                            <?php if(isset($_SESSION['user_id'])): ?>
                                <li class="scroll-to-section"><a href="profile.php">Профиль</a></li>
                            <?php else: ?>
                                <li class="scroll-to-section"><a href="login.php">Авторизация</a></li>
                            <?php endif; ?>

                            <?php if(isset($_SESSION['user_id']) && getUser(($_SESSION['user_id']))['role'] == 'admin'): ?>
                                <li class="scroll-to-section"><a href="admin/users.php">Управление</a></li>
                            <?php endif; ?>

                            <?php if(isset($_SESSION['user_id'])): ?>
                                <li class="scroll-to-section"><a href="cart.php"><?php if(!(getCartItems($_SESSION['user_id']))): ?><i class='bx bx-cart-alt' style="font-size: 18px; padding-top:10px"></i><?php else: ?><i class='bx bxs-cart-alt' style="font-size: 18px; padding-top:10px"></i> <?php endif; ?></a></li>
                            <?php endif; ?>
                        </ul>        
                        <a class='menu-trigger'>
                            <span>Menu</span>
                        </a>
                        <!-- ***** Menu End ***** -->
                    </nav>
                </div>
            </div>
        </div>
    </header>