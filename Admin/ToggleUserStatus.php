<?php
include("Connection.php");

$id = $_GET['id'];

// Get current status
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Status FROM Users WHERE UserId=$id"));

if($user){
    $newStatus = ($user['Status'] == 'Block') ? 'Unblock' : 'Block';

    mysqli_query($conn, "UPDATE Users SET Status='$newStatus' WHERE UserId=$id");
}

header("Location: AdminManageUser.php");
exit();
?>

