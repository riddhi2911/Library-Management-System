<?php
session_start();

if(isset($_POST['book_id']) && isset($_POST['qty'])){

    $book_id = $_POST['book_id'];
    $qty = (int)$_POST['qty'];

    //  Prevent invalid values
    if($qty < 1){
        $qty = 1;
    }

    // Update cart
    if(isset($_SESSION['cart'][$book_id])){
        $_SESSION['cart'][$book_id]['qty'] = $qty;
    }
}

//  Redirect back
header("Location: Cart.php");
exit();
?>