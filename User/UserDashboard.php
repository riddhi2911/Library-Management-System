<?php
session_start();
include('Connection.php');

$user_id = $_SESSION['UserId'];

/* ===== TOTAL ISSUED ===== */
$issued_query = "SELECT COUNT(*) as total FROM IssueBook WHERE UserId='$user_id'";
$issued_result = mysqli_query($conn, $issued_query);
$totalIssued = mysqli_fetch_assoc($issued_result)['total'];

/* ===== TOTAL RETURNED ===== */
$return_query = "
SELECT COUNT(*) as total 
FROM ReturnBook
JOIN IssueBook ON ReturnBook.IssueId = IssueBook.IssueId
WHERE IssueBook.UserId = '$user_id'
";

$return_result = mysqli_query($conn, $return_query);
$totalReturned = mysqli_fetch_assoc($return_result)['total'];

/* ===== TOTAL FINE PAID ===== */
$fine_query = "
SELECT SUM(Fine.FineAmount) as total 
FROM Fine
JOIN IssueBook ON Fine.IssueId = IssueBook.IssueId
WHERE IssueBook.UserId = '$user_id'
";

$fine_result = mysqli_query($conn, $fine_query);
$fine_data = mysqli_fetch_assoc($fine_result);

$totalFine = $fine_data['total'] ? $fine_data['total'] : 0;

// ✅ Fetch categories
$cat_query = "SELECT * FROM Categories";
$cat_result = mysqli_query($conn, $cat_query);

// ✅ Get selected category
$category_id = isset($_GET['category']) ? $_GET['category'] : "";

// ✅ Fetch books
if($category_id != ""){
    $query = "SELECT * FROM Book WHERE CategoryId='$category_id'";
}else{
    $query = "SELECT * FROM Book";
}

$result = mysqli_query($conn, $query);

// Cart count
$cart_count = 0;

if(isset($_SESSION['cart'])){
    $cart_count = count($_SESSION['cart']);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


<style>
    /*===== Full Page =====*/
    body {
        margin: 0;
        font-family: Arial, Helvetica, sans-serif;
        background-color: #ffffff;
    }

    /* ===== Header ===== */
    .header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background-color: #2c3e50;
        color: white;
        padding: 30px 40px;
    }

    /* ===== Logo =====*/
    .logo-section {
        display: flex;
        align-items: center;
        gap: 35px;
    }

    .logo {
        width: 45px;
        height: 45px;
        background-color: #f1c40f;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-weight: bold;
        color: #2c3e50;
    }

    .logo img{
        width: 70px;
        height: auto;
    }

    /* ===== Site Name =====*/
    .site-name {
        font-size: 28px;
        font-weight: bold;
    }

    /* Right side alignment */
    .right-section {
        display: flex;
        align-items: center;
        gap: 10px; /* 👈 CONTROL SPACE HERE */
    }

    /* ===== Search Bar ===== */
    /* .search-bar {
        background: white;
        padding: 15px 30px;
        border-radius: 100px;
        display: flex;
        align-items: center;
    }

    .search-bar i {
        color: gray;
        margin-right: 8px;
    }

    .search-bar input {
        border: none;
        outline: none;
        width: 200px;
    } */

    /* Cart Icon */
    .cart-icon {
        position: relative;
        color: white;
        font-size: 25px;
        text-decoration: none;
    }

    .cart-icon:hover {
        color: #f1c40f;
    }

    /* Cart Count Badge */
    .cart-count {
        position: absolute;
        top: -8px;
        right: -10px;
        background: red;
        color: white;
        font-size: 12px;
        padding: 3px 6px;
        border-radius: 50%;
    }

    /* ===== Layout ===== */
    .container {
        display: flex;
        height: calc(100vh - 70px);
    }

    /* ===== Sidebar ===== */
    .sidebar {
        width: 250px;
        background-color: #34495e;
        color: white;
        padding: 20px;
    }

    .sidebar h3 {
        text-align: center;
        margin-bottom: 20px;
    }

    .sidebar a {
        display: block;
        color: white;
        text-decoration: none;
        padding: 10px;
        margin-bottom: 10px;
        background-color: #3d566e;
        border-radius: 5px;
    }

    .sidebar a i {
        width: 25px;         
        margin-right: 10px;  
        text-align: center; 
    }

    .sidebar a:hover {
        background-color: #1abc9c;
    }

    /* ✅ CATEGORY DROPDOWN */
    .category-menu {
        position: relative;
    }

    .category-menu > a {
        pointer-events: none;
    }

    .category-list {
        display:none;
        position:absolute;
        left:100%;
        top:0;
        background:#fff;
        min-width:180px;
        box-shadow:0 2px 8px rgba(0,0,0,0.2);
        border-radius:5px;
    }

    .category-menu:hover .category-list {
        display:block;
    }

    .category-list a {
        display:block;
        padding:10px;
        color:#333;
        text-decoration:none;
    }

    .category-list a:hover {
        background:#1abc9c;
        color:#fff;
    }

    /* ===== Main ===== */
    .main {
        flex: 1;
        padding: 20px;
    }

    /* ===== Dashboard Cards ===== */
    .cards {
        display: flex;
        gap: 25px;
        margin-top: 30px;
    }

    .card {
        flex: 1;
        padding: 30px 150px;
        height: 150px; /* 👈 fixed height */
        color: white;
        border-radius: 12px;
        cursor: pointer;
        transition: 0.3s;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .card:hover {
        transform: scale(1.05);
    }

    .card h2 {
        font-size: 40px;
    }

    .card p {
        font-size: 20px;
    }

    .card button {
        margin-top: 10px;
        padding: 6px 12px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    /* Colors */
    .blue {
        background: #1abc9c;
    }

    .darkblue {
        background: #2980b9;
    }

    .green {
        background: #27ae60;
    }
</style>

</head>

<body>
    <!-- ===== Header ===== -->
    <div class="header">

        <!-- LOGO + SITE NAME -->
        <div class="logo-section">
            <div class="logo">
                <img src="logo.jpeg" alt="BookSpark Logo">
            </div>
            <div class="site-name"><?php echo (!empty($_SESSION['site_name'])) ? $_SESSION['site_name'] : 'Book Spark'; ?></div>
        </div>

        <!-- RIGHT SIDE: SEARCH + CART -->
        <div class="right-section">

            <!-- CART ICON
            <a href="Cart.php" class="cart-icon">
                <i class="fa fa-shopping-cart"></i>
                <?php
                $cart_count = 0;
                if(isset($_SESSION['user_id']) && isset($_SESSION['cart'][$_SESSION['user_id']])){
                    $cart_count = count($_SESSION['cart'][$_SESSION['user_id']]);
                }
                ?>
                <span class="cart-count"><?= $cart_count; ?></span>
            </a> -->
        </div>
    </div>
    <div class="container">

        <div class="sidebar">
            <h3>User Menu</h3>
            <a href="UserDashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a>
            <a href="UserBooks.php"><i class="fa fa-book"></i> Books</a>

            <!-- CATEGORY DROPDOWN -->
            <div class="category-menu">
                <a href="#"><i class="fa fa-list"></i> Categories ▸</a>

                <div class="category-list">
                    <a href="UserIndex.php">All</a>

                    <?php while($cat = mysqli_fetch_assoc($cat_result)){ ?>
                    <a href="UserIndex.php?category=<?= $cat['CategoryId']; ?>">
                        <?= $cat['CategoryName']; ?>
                    </a>
                    <?php } ?>
                </div>
            </div>
            <a href="UserFine.php"><i class="fa fa-pencil"></i> Pay Fine</a>
            <a href="UserProfile.php"><i class="fa fa-user"></i> My Profile</a>
            <a href="UserLogout.php"><i class="fa fa-sign-out"></i> Logout</a>
        </div>


    <!-- Main Content -->
    <div class="main">

        <div class="topbar">
            <h1>Dashboard</h1>
        </div>

        <div class="cards">

            <div class="cards">

    <!-- Issued -->
    <div class="card blue" onclick="window.location='IssueBookHistory.php'">
        <h2><?php echo $totalIssued; ?></h2>
        <p>Total Issued Books</p>
    </div>

    <!-- Returned -->
    <div class="card darkblue" onclick="window.location='ReturnBookHistory.php'">
        <h2><?php echo $totalReturned; ?></h2>
        <p>Total Returned Books</p>
    </div>

    <!-- Fine -->
    <div class="card green" onclick="window.location='FineHistory.php'">
        <h2>₹<?php echo $totalFine; ?></h2>
        <p>Total Fine Paid</p>
    </div>

</div>
            </div>

        </div>

    </div>

</div>


</body>
</html>