<?php
session_start();
include("Connection.php");

// Check login
if(!isset($_SESSION['UserId'])){
    header("Location: UserLogin.php");
    exit();
}

$user_id = $_SESSION['UserId'];

// Ensure msg is always array (FIX ERROR)
if(!isset($_SESSION['msg']) || !is_array($_SESSION['msg'])){
    $_SESSION['msg'] = [];
}

// Cart
if(!isset($_SESSION['cart'][$user_id])){
    $_SESSION['cart'][$user_id] = [];
}

$cart = $_SESSION['cart'][$user_id];

// Remove
if(isset($_GET['remove'])){
    $remove_id = $_GET['remove'];
    $_SESSION['cart'][$user_id] = array_diff($_SESSION['cart'][$user_id], [$remove_id]);

    // Remove request tracking
    if(isset($_SESSION['requested_books'][$remove_id])){
        unset($_SESSION['requested_books'][$remove_id]);
    }

    header("Location: Cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Your Cart</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body { margin:0; font-family:'Segoe UI'; }
.header { display:flex; justify-content:space-between; background:#2c3e50; color:white; padding:30px 40px; }
.logo-section { display:flex; gap:35px; }
.logo img { width:90px; }
.site-name { font-size:40px; font-weight:bold; }
.container { padding:30px; }
.back-btn { padding:8px 15px; background:#2c3e50; color:white; text-decoration:none; border-radius:6px; }
.title { text-align:center; font-size:35px; margin-bottom:20px; }
.cart-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:20px; }
.card { background:#fff; padding:15px; border-radius:12px; box-shadow:0 2px 10px rgba(0,0,0,0.1); text-align:center; }
.card img { width:100%; height:150px; object-fit:contain; }
.btn { display:block; margin-top:8px; padding:8px; border-radius:5px; color:white; text-decoration:none; }
.remove { background:#e74c3c; }
.request { background:#3498db; }
.return { background:#2ecc71; }
.msg { padding:6px; border-radius:5px; margin-top:5px; color:white; }
.pending { color:gray; }
.error { color:red; }
.success { color:green; }
.empty { text-align:center; margin-top:50px; }
</style>
</head>

<body>

<div class="header">
    <div class="logo-section">
        <div class="logo"><img src="logo.jpeg"></div>
        <div class="site-name">
            <?= (!empty($_SESSION['site_name'])) ? $_SESSION['site_name'] : 'Book Spark'; ?>
        </div>
    </div>
</div>

<div class="container">
<a href="UserBooks.php" class="back-btn">← Back</a>
<div class="title"><b>My Cart</b></div>

<?php if(empty($cart)){ ?>

<div class="empty">No books found</div>

<?php } else { ?>

<div class="cart-grid">

<?php
foreach($cart as $id){

    // Book
    $book = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM Book WHERE BookId='$id'"));

    $image = (!empty($book['BookLink'])) ?
        (filter_var($book['BookLink'], FILTER_VALIDATE_URL) ? $book['BookLink'] : "uploads/".$book['BookLink'])
        : "images/default.jpg";

    // Issue status
    $issue_status = '';
    $issue_result = mysqli_query($conn,"
        SELECT Status FROM IssueBook 
        WHERE BookId='$id' AND UserId='$user_id'
        ORDER BY IssueId DESC LIMIT 1
    ");

    if($issue_result && mysqli_num_rows($issue_result) > 0){
        $row = mysqli_fetch_assoc($issue_result);
        $issue_status = strtolower($row['Status']);
    }

    // Request status (only if requested in session)
    $request_status = '';

    if(isset($_SESSION['requested_books'][$id])){

        $request_result = mysqli_query($conn,"
            SELECT Status FROM RequestedBook 
            WHERE BookId='$id' AND UserId='$user_id'
            ORDER BY RequestId DESC LIMIT 1
        ");

        if($request_result && mysqli_num_rows($request_result) > 0){
            $row = mysqli_fetch_assoc($request_result);
            $request_status = strtolower($row['Status']);
        }
    }
?>

<div class="card">
<img src="<?= $image; ?>" onerror="this.src='images/default.jpg';">

<h4><?= $book['Title']; ?></h4>
<p><?= $book['Author']; ?></p>

<!-- MESSAGE (PER BOOK) -->
<?php if(isset($_SESSION['msg'][$book['BookId']])){ ?>
    <p class="msg success">
        <?= $_SESSION['msg'][$book['BookId']]; ?>
    </p>
<?php unset($_SESSION['msg'][$book['BookId']]); } ?>

<?php if($issue_status == "issued"){ ?>

    <form method="POST" action="ReturnBook.php">
        <input type="hidden" name="book_id" value="<?= $book['BookId']; ?>">
        <button type="submit" name="return_book" class="btn return">
            Return Book
        </button>
    </form>

<?php } else { ?>

    <!-- STATUS -->
    <?php if($request_status == "pending"){ ?>
        <p class="msg pending">Request Pending</p>
    <?php } ?>

    <?php if($request_status == "rejected"){ ?>
        <p class="msg error">Your request was rejected by admin</p>
    <?php } ?>

    <?php if($request_status == "approve"){ ?>
        <p class="msg success">Request Approved</p>
    <?php } ?>

    <!-- REMOVE -->
    <?php if($request_status != "pending"){ ?>
        <a href="Cart.php?remove=<?= $book['BookId']; ?>" class="btn remove">Remove</a>
    <?php } ?>

    <!-- REQUEST -->
    <?php if($request_status != "pending" && $issue_status != "issued"){ ?>
        <a href="RequestBook.php?id=<?= $book['BookId']; ?>" class="btn request">Request Book</a>
    <?php } ?>

<?php } ?>

</div>

<?php } ?>

</div>

<?php } ?>

</div>

</body>
</html>