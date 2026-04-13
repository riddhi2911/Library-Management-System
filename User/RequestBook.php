<?php
session_start();
include("Connection.php");

$user_id = $_SESSION['UserId'];
$book_id = $_GET['id'];

$check = mysqli_query($conn,"
SELECT * FROM RequestedBook 
WHERE UserId='$user_id' AND BookId='$book_id' AND Status='Pending'
");

if(mysqli_num_rows($check) == 0){
    mysqli_query($conn,"
    INSERT INTO RequestedBook (UserId,BookId,RequestDate,Status)
    VALUES ('$user_id','$book_id',CURDATE(),'Pending')
    ");
    $_SESSION['msg']="Request Sent!";
}else{
    $_SESSION['msg']="Already Requested!";
}

header("Location: Cart.php");