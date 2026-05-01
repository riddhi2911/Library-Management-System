<?php
session_start();
include("Connection.php");

/* CHECK ID */
if(!isset($_GET['id'])){
    header("Location: UserBooks.php");
    exit();
}

$id = $_GET['id'];
$user_id = $_SESSION['UserId'];

/*ENSURE MSG IS ARRAY */
if(!isset($_SESSION['msg']) || !is_array($_SESSION['msg'])){
    $_SESSION['msg'] = [];
}

/* CHECK USER STATUS */
$user_result = mysqli_query($conn,"
    SELECT Status FROM Users WHERE UserId='$user_id'
");

$user = mysqli_fetch_assoc($user_result);

/* CART INIT */
if(!isset($_SESSION['cart'][$user_id])){
    $_SESSION['cart'][$user_id] = [];
}

/* ADD TO CART */
if(!in_array($id, $_SESSION['cart'][$user_id])){
    $_SESSION['cart'][$user_id][] = $id;

    $_SESSION['msg'][$id] = "Book added to cart!";
}else{
    $_SESSION['msg'][$id] = "Book already in cart!";
}

/* REDIRECT */
header("Location: Cart.php");
exit();
?>