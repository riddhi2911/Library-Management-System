<?php
include("Connection.php");
session_start();

$id = $_GET['id'];

// Get request data
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM RequestedBook WHERE RequestId=$id"));

// Insert into IssueBook
$borrow_days = isset($_SESSION['borrow_days']) ? $_SESSION['borrow_days'] : 7; // from settings

mysqli_query($conn, "INSERT INTO IssueBook (UserId, BookId, IssueDate, DueDate, Status) VALUES ('{$data['UserId']}', '{$data['BookId']}', CURDATE(), DATE_ADD(CURDATE(), INTERVAL $borrow_days DAY), 'Issued')");
// Update request status
mysqli_query($conn, "UPDATE RequestedBook SET Status='Approved' WHERE RequestId=$id");

// Reduce book quantity
mysqli_query($conn, "UPDATE Book SET Quantity = Quantity - 1 WHERE BookId = '{$data['BookId']}'");

header("Location: AdminRequestBook.php");
?>