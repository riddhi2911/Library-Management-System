<?php
include("Connection.php");

$id = $_GET['id'];

// Delete query
$query = "DELETE FROM Book WHERE BookId = $id";

if(mysqli_query($conn, $query)){
    header("Location: AdminManageBook.php");
} else {
    echo "Error: " . mysqli_error($conn);
}
?>