
<div id="preloader">
        <div class="jumper">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div> 

<header class="header-area header-sticky">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="main-nav">
                        <!-- ***** Logo Start ***** -->
                        <a href="users.php" class="logo" style="color: black">
                            Admin Panel
                        </a>
                        <!-- ***** Logo End ***** -->
                        <!-- ***** Menu Start ***** -->
                        <ul class="nav">
                            <li class="scroll-to-section"><a href="../index.php">Вернуться назад</a></li>
                            <li class="scroll-to-section"><a href="users.php">Пользователи</a></li>
                            <li class="submenu">
                                <a href="javascript:;">Категории</a>
                                <ul>
                                    <li><a href="categories.php">Все категории</a></li>
                                    <li><a href="add_category.php">Добавить категорию</a></li>
                                </ul>
                            </li>
                            <li class="submenu">
                                <a href="javascript:;">Книги</a>
                                <ul>
                                    <li><a href="books.php">Все книги</a></li>
                                    <li><a href="add_book.php">Добавить книгу</a></li>
                                </ul>
                            </li>
                            <?php if($_SESSION['user_id']): ?>
                                <li class="scroll-to-section"><a href="../profile.php">Профиль</a></li>
                            <?php else: ?>
                                <li class="scroll-to-section"><a href="login.php">Авторизация</a></li>
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