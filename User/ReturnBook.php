<?php
session_start();
include("Connection.php");

$user_id = $_SESSION['UserId'];

// Ensure msg is always array
if(!isset($_SESSION['msg']) || !is_array($_SESSION['msg'])){
    $_SESSION['msg'] = [];
}

if(isset($_POST['return_book']) && isset($_POST['book_id']))
{
    $book_id = $_POST['book_id'];

    // Get IssueId from issuebook
    $issue_query = "SELECT IssueId FROM issuebook 
                    WHERE BookId='$book_id' 
                    AND UserId='$user_id' 
                    AND Status='Issued'
                    LIMIT 1";

    $issue_result = mysqli_query($conn, $issue_query);

    if(mysqli_num_rows($issue_result) > 0)
    {
        $issue_row = mysqli_fetch_assoc($issue_result);
        $IssueId = $issue_row['IssueId'];

        $ReturnDate = date("Y-m-d H:i:s");

        // Insert into returnbook
        $query = "INSERT INTO returnbook (IssueId, ReturnDate, Status)
                  VALUES ('$IssueId', '$ReturnDate', 'Returned')";

        if(mysqli_query($conn, $query))
        {
            // Update issuebook status
            mysqli_query($conn, "
                UPDATE issuebook 
                SET Status='Returned' 
                WHERE IssueId='$IssueId'
            ");

            // Increase book quantity
            mysqli_query($conn, "
                UPDATE Book 
                SET Quantity = Quantity + 1 
                WHERE BookId='$book_id'
            ");

            // Remove from cart (correct structure)
            if(isset($_SESSION['cart'][$user_id])){
                $_SESSION['cart'][$user_id] = array_diff(
                    $_SESSION['cart'][$user_id],
                    [$book_id]
                );
            }

            // Store message per book
            $_SESSION['msg'][$book_id] = "Book returned successfully!";
        }
        else
        {
            $_SESSION['msg'][$book_id] = "Database error!";
        }
    }
    else
    {
        $_SESSION['msg'][$book_id] = "No issued record found!";
    }
}

header("Location: Cart.php");
exit();
?>