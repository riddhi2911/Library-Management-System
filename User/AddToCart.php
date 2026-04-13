<?php
session_start();
include('Connection.php');

// Check login
if(!isset($_SESSION['UserId'])){
    header("Location: Login.php");
    exit();
}

$user_id = $_SESSION['UserId'];

$id = $_GET['id'];

// Create user-specific cart
if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

if(!isset($_SESSION['cart'][$user_id])){
    $_SESSION['cart'][$user_id] = [];
}

// Avoid duplicate
if(!in_array($id, $_SESSION['cart'][$user_id])){
    $_SESSION['cart'][$user_id][] = $id;
}

// Optional message
$_SESSION['msg'] = "Book added to cart successfully";

header("Location: UserIndex.php");
exit();
?>