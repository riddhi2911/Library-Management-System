<?php
include("Connection.php");

$id = $_GET['id'];

mysqli_query($conn, "UPDATE RequestedBook 
SET Status='Rejected' 
WHERE RequestId=$id");

header("Location: AdminRequestBook.php");
?>