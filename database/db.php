<?php

$host = "localhost";
$username = "root";
$password = "";
$dbname = "book_shop";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



// Books Functions

if(!function_exists('getBooks')){
    function getBooks(){
         global $conn;
         $sql = "SELECT * FROM books";
         $result = $conn->query($sql);
         if($result->num_rows > 0){
             $books = array();
             while($row = $result->fetch_assoc()){
                 $books[] = $row;
             }
             return $books;
         }else{
             return false;
         }

     }
 }


if(!function_exists('getBook')){
    function getBook($id){
        global $conn;
        $sql = "SELECT * FROM books WHERE id = '$id'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $book = $result->fetch_assoc();
            return $book;
        }else{
            return false;
        }
    }
}

if(!function_exists('getBooksByCategory')){

    function getBooksByCategory($id){
        global $conn;
        $sql = "SELECT * FROM books WHERE category_id = '$id'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $books = array();
            while($row = $result->fetch_assoc()){
                $books[] = $row;
            }
            return $books;
        }else{
            return false;
        }
    }

}



if(!function_exists('searchBooks')){
    function searchBooks($search){
        global $conn;
        $sql = "SELECT * FROM books WHERE name LIKE '%$search%' OR author LIKE '%$search%'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $books = array();
            while($row = $result->fetch_assoc()){
                $books[] = $row;
            }
            return $books;
        }else{
            return false;
        }
    }
}

 if (!function_exists('storeBook')) {
    function storeBook($name, $author, $price, $description, $image, $user_id, $category_id)
    {
        global $conn;

        // Construct the full path to the image
        $uploadDir = './uploads/';
        $fullImagePath = $uploadDir . $image;

        // Use prepared statements to prevent SQL injection
        $sql = "INSERT INTO books (name, author, price, description, image, user_id, category_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bind_param("ssdssii", $name, $author, $price, $description, $fullImagePath, $user_id, $category_id);

        // Execute the statement
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}


if (!function_exists('updateBook')) {
    function updateBook($id, $name, $author, $description, $price, $category_id, $image)
    {
        global $conn;

        $uploadDir = './uploads/';
        $fullImagePath = $uploadDir . $image;

        // Проверка, нужно ли обновлять изображение
        $imageSql = "";
        if (!empty($image)) {
            $imageSql = ", image = '$image'";
        }

        $sql = "UPDATE books SET name = '$name', author = '$author', description = '$description', price = '$price', category_id = '$category_id' $imageSql WHERE id = '$id'";

        if ($conn->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }
    }
}



if(!function_exists('deleteBook')){
    function deleteBook($id){
        global $conn;
        $sql = "DELETE FROM books WHERE id = '$id'";
        if($conn->query($sql) === TRUE){
            return true;
        }else{
            return false;
        }
    }
}


if (!function_exists('rateBook')) {

    function rateBook($book_id, $user_id, $rating)
    {
       global $conn;

        $sql = "SELECT * FROM users_books WHERE book_id = ? AND user_id = ?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param("ii", $book_id, $user_id);

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // update
            $sql = "UPDATE users_books SET rating = ? WHERE book_id = ? AND user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iii", $rating, $book_id, $user_id);
            $stmt->execute();
        } else {
            // insert
            $sql = "INSERT INTO users_books (book_id, user_id, rating) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iii", $book_id, $user_id, $rating);
            $stmt->execute();
        }
        
    }
}

if(!function_exists('getAverageRating')){
    function getAverageRating($book_id){

    global $conn;

    $sql = "SELECT AVG(rating) AS avg_rating FROM users_books WHERE book_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        return $row['avg_rating'];
    } else {
        return 0;
    }
}
}


if(!function_exists('saveBook')){
    
    function saveBook($user_id, $book_id){

        global $conn;

        $sql = "SELECT * FROM saved_books WHERE user_id = ? AND book_id = ?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param("ii", $user_id, $book_id);

        $stmt->execute();

        $result = $stmt->get_result();

        if($result->num_rows > 0){
            $sql = "DELETE FROM saved_books WHERE user_id = ? AND book_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $user_id, $book_id);
            $stmt->execute();

            header("Location: shop.php?book_unsaved=true");

        }else{
            $sql = "INSERT INTO saved_books (user_id, book_id) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $user_id, $book_id);
            $stmt->execute();

            header("Location: shop.php?book_saved=true");
        }
    
    }
}

if(!function_exists('getSavedBook')){
    function getSavedBook($user_id, $book_id){
        
        global $conn;
        $sql = "SELECT * FROM saved_books WHERE user_id = ? AND book_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $book_id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if($result->num_rows > 0){
            return true;
        }else{
            return false;
        }
    }

}

if (!function_exists('getSavedBooksDetails')) {
    function getSavedBooksDetails($user_id)
    {
        global $conn;

        $sql = "SELECT books.* FROM saved_books
                JOIN books ON saved_books.book_id = books.id
                WHERE saved_books.user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $books = array();
            while ($row = $result->fetch_assoc()) {
                $books[] = $row;
            }
            return $books;
        } else {
            return false;
        }
    }
}



// Auth Functions


if(!function_exists('registerUser')){
    function registerUser($firstname, $surname, $email, $password, $image, $role){
        global $conn;

        $uploadDir = './uploads/';
        $fullImagePath = $uploadDir . $image;

        $sql = "INSERT INTO users (firstname, surname, email, password, image, role) VALUES ('".$firstname."', '".$surname."', '".$email."', '".$password."', '".$fullImagePath."', '".$role."')";
        if($conn->query($sql) === TRUE){
            return true;
        }else{
            return false;
        }
    }
}


// User Functions

if(!function_exists('getUser')){
    function getUser($id){
        global $conn;
        $sql = "SELECT * FROM users WHERE id = '$id'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $user = $result->fetch_assoc();
            return $user;
        }else{
            return false;
        }
    }
}


if(!function_exists('getUsers')){
    function getUsers(){
        global $conn;
        $sql = "SELECT * FROM users";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $users = array();
            while($row = $result->fetch_assoc()){
                $users[] = $row;
            }
            return $users;
        }else{
            return false;
        }
    }
}

if(!function_exists('updateUser')){
    function updateUser($id, $firstname, $surname, $email, $role){
        global $conn;
        $sql = "UPDATE users SET firstname = '$firstname', surname = '$surname', email = '$email', role = '$role' WHERE id = '$id'";
        if($conn->query($sql) === TRUE){
            return true;
        }else{
            return false;
        }
    }
}


// Categories Functions

if(!function_exists('getCategories')){
    function getCategories(){
        global $conn;
        $sql = "SELECT * FROM categories";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $categories = array();
            while($row = $result->fetch_assoc()){
                $categories[] = $row;
            }
            return $categories;
        }else{
            return false;
        }
    }
}

if(!function_exists('getCategory')){
    function getCategory($id){
        global $conn;
        $sql = "SELECT * FROM categories WHERE id = '$id'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $category = $result->fetch_assoc();
            return $category;
        }else{
            return false;
        }
    }
}


if(!function_exists('storeCategory')){
    function storeCategory($name){
        global $conn;
        $sql = "INSERT INTO categories (name) VALUES ('".$name."')";
        if($conn->query($sql) === TRUE){
            return true;
        }else{
            return false;
        }
    }
}

if(!function_exists('updateCategory')){
    function updateCategory($id, $name){
        global $conn;
        $sql = "UPDATE categories SET name = '$name' WHERE id = '$id'";
        if($conn->query($sql) === TRUE){
            return true;
        }else{
            return false;
        }
    }
}

if(!function_exists('deleteCategory')){
    function deleteCategory($id){
        global $conn;
        $sql = "DELETE FROM categories WHERE id = '$id'";
        if($conn->query($sql) === TRUE){
            return true;
        }else{
            return false;
        }
    }
}


// Cart functions

if(!function_exists('addToCart')){
    function addToCart($user_id, $book_id, $amount, $total){

        global $conn;

        $sql = "SELECT * FROM cart WHERE user_id = ? AND book_id = ?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param("ii", $user_id, $book_id);

        $stmt->execute();

        $result = $stmt->get_result();

        if($result->num_rows > 0){
            $sql = "UPDATE cart SET amount = amount + ? WHERE user_id = ? AND book_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iii", $amount, $user_id, $book_id);
            $stmt->execute();

            header("Location: cart.php?added_to_cart=true");

        }else{
            $sql = "INSERT INTO cart (user_id, book_id, amount, total) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iiid", $user_id, $book_id, $amount, $total);
            $stmt->execute();

            header("Location: cart.php?added_to_cart=true");
        }
    }
}

if(!function_exists('getCartItems')){
    function getCartItems($user_id){
        global $conn;
        $sql = "SELECT cart.id as cart_id, cart.*, books.name, books.price, books.image, books.category_id FROM cart
                JOIN books ON cart.book_id = books.id
                WHERE cart.user_id = '$user_id'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $cartItems = array();
            while($row = $result->fetch_assoc()){
                $cartItems[] = $row;
            }
            return $cartItems;
        } else {
            return false;
        }
    }
}


if(!function_exists('deleteCartItem')){
    function deleteCartItem($id){
        global $conn;
        $sql = "DELETE FROM cart WHERE id = '$id'";
        if($conn->query($sql) === TRUE){
            return true;
        }else{
            return false;
        }
    }
}

if (!function_exists('updateCartItem')) {
    function updateCartItem($id, $amount, $total)
    {
        global $conn;

        if ($amount == 0) {
            $sql = "DELETE FROM cart WHERE id = '$id'";
        } else {
            $sql = "UPDATE cart SET amount = '$amount', total = '$total' WHERE id = '$id'";
        }

        if ($conn->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }
    }
}


if(!function_exists('getTotalPrice')){
    function getTotalPrice($user_id){
        global $conn;
        $sql = "SELECT SUM(books.price * cart.amount) AS total_price FROM cart
                JOIN books ON cart.book_id = books.id
                WHERE cart.user_id = '$user_id'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $totalPrice = $result->fetch_assoc();
            return $totalPrice['total_price'];
        }else{
            return false;
        }
    }
}


// Profile functions

if (!function_exists('updateProfile')) {
    function updateProfile($id, $firstname, $surname, $email)
    {
        global $conn;

        $stmt = $conn->prepare("UPDATE users SET firstname = ?, surname = ?, email = ? WHERE id = ?");
        $stmt->bind_param("sssi", $firstname, $surname, $email, $id);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('updatePassword')) {
    function updatePassword($id, $password)
    {
        global $conn;

        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $password, $id);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}

?>
