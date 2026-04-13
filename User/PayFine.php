<?php
include('Connection.php');

$id = $_GET['id'];

$result = mysqli_query($conn, "SELECT * FROM issued_books WHERE id=$id");
$row = mysqli_fetch_assoc($result);

$fine = $row['fine'];

if(isset($_POST['pay'])){
    mysqli_query($conn, "UPDATE issued_books SET fine=0 WHERE id=$id");

    echo "<h2>Payment Successful!</h2>";
    echo "<a href='return.php'>Go Back</a>";
}
?>

<h2>Fine Amount: ₹<?php echo $fine; ?></h2>

<form method="post">
    <button name="pay">Pay Now</button>
</form>