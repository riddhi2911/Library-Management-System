<?php 
session_start();
include("Connection.php");

$user_id = $_SESSION['UserId'];
$book_id = $_GET['id'];

// Ensure msg is array
if(!isset($_SESSION['msg']) || !is_array($_SESSION['msg'])){
    $_SESSION['msg'] = [];
}

// ✅ CHECK BOOK QUANTITY FIRST
$book_result = mysqli_query($conn, "
    SELECT Quantity FROM Book WHERE BookId='$book_id'
");

if($book_result && mysqli_num_rows($book_result) > 0){

    $book_row = mysqli_fetch_assoc($book_result);
    $quantity = $book_row['Quantity'];

    // ❌ If no stock
    if($quantity <= 0){
        $_SESSION['msg'][$book_id] = "Book is out of stock!";
        header("Location: Cart.php");
        exit();
    }

} else {
    $_SESSION['msg'][$book_id] = "Book not found!";
    header("Location: Cart.php");
    exit();
}

// ✅ CHECK EXISTING PENDING REQUEST
$check = mysqli_query($conn,"
SELECT * FROM RequestedBook 
WHERE UserId='$user_id' 
AND BookId='$book_id' 
AND Status='Pending'
");

if(mysqli_num_rows($check) == 0){

    mysqli_query($conn,"
    INSERT INTO RequestedBook (UserId,BookId,RequestDate,Status)
    VALUES ('$user_id','$book_id',CURDATE(),'Pending')
    ");

    // Track request
    $_SESSION['requested_books'][$book_id] = true;

    $_SESSION['msg'][$book_id] = "Request Sent!";

}else{
    $_SESSION['msg'][$book_id] = "Already Requested!";
}

header("Location: Cart.php");
exit();
?>