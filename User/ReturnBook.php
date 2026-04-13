<?php
session_start();
include("Connection.php");

if(isset($_POST['return_book']) && isset($_POST['book_id']))
{
    $book_id = $_POST['book_id'];
    $user_id = $_SESSION['UserId'];

    // Get IssueId from issuebook table
    $issue_query = "SELECT IssueId FROM issuebook 
                    WHERE BookId='$book_id' AND UserId='$user_id' AND Status='Issued'
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
            mysqli_query($conn, "UPDATE issuebook SET Status='Returned' WHERE IssueId='$IssueId'");

            // Remove from cart
            $_SESSION['cart'] = array_diff($_SESSION['cart'], [$book_id]);

            $_SESSION['msg'] = "Book returned successfully!";
        }
        else
        {
            $_SESSION['msg'] = "Database error: " . mysqli_error($conn);
        }
    }
    else
    {
        $_SESSION['msg'] = "No issued record found!";
    }
}

header("Location: Cart.php");
exit();
?>