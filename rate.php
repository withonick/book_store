<?php

include_once('./database/db.php');

session_start();

// Ваш PHP-код для обработки оценки и записи в базу данных
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookId = $_POST['book_id'];
    $rating = $_POST['rating'];
    $userId = $_SESSION['user_id']; // Замените на ваш способ получения ID пользователя

    // Вызов функции rateBook с полученными данными
    rateBook($bookId, $userId, $rating);

    // Ответ в случае успеха
    
    header('Location: book.php?id=' . $bookId . '&rating_added=true');
}

?>
