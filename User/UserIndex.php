<?php
include('Connection.php');
session_start();

// Fetch categories
$cat_query = "SELECT * FROM Categories";
$cat_result = mysqli_query($conn, $cat_query);

// Get filters
$category_id = isset($_GET['category']) ? $_GET['category'] : "";
$search = isset($_GET['search']) ? $_GET['search'] : "";

//  Fetch books
if($category_id != "" && $search != ""){
    $query = "SELECT * FROM Book 
              WHERE CategoryId='$category_id' 
              AND Author LIKE '%$search%'";
}
elseif($category_id != ""){
    $query = "SELECT * FROM Book WHERE CategoryId='$category_id'";
}
elseif($search != ""){
    $query = "SELECT * FROM Book WHERE Author LIKE '%$search%'";
}
else{
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
<title>User Home | Library System</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    /*===== Full Page =====*/
    body{
        margin:0;
        font-family:Arial;
        background-color:#ffffff;
    }

    /* ===== Top Panel ===== */
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
    .search-bar {
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
    }

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

    /* MAIN */
    .main{
        display:flex;
        min-height:100vh;
    }

    .panal-header {
        background: #145a6f;
        color: white;
        padding: 5px;
        border-radius: 5px;
    }
    /* SIDEBAR */
    .sidebar{
        width:250px;
        background:#34495e;
        color:#fff;
        padding:20px;
    }

    .sidebar a{
        display:block;
        color:#fff;
        padding:10px;
        margin-bottom:10px;
        background:#3d566e;
        border-radius:5px;
        text-decoration:none;
    }

    .sidebar a i {
        width: 25px;         
        margin-right: 10px;  
        text-align: center; 
    }

    .sidebar a:hover{
        background:#1abc9c;
    }

    /* CATEGORY DROPDOWN */
    .category-menu{
        position:relative;
    }

    .category-menu > a{
        pointer-events:none;
    }

    .category-list{
        display:none;
        position:absolute;
        left:100%;
        top:0;
        background:#fff;
        min-width:180px;
        border-radius:5px;
    }

    .category-menu:hover .category-list{
        display:block;
    }

    .category-list a{
        color:#333;
        padding:10px;
        display:block;
    }

    .category-list a:hover{
        background:#1abc9c;
        color:#fff;
    }

    /* CONTENT */
    .content{
        flex:1;
        padding:20px;
    }

    /* GRID */
    .books{
        display:grid;
        grid-template-columns:repeat(4,1fr);
        gap:20px;
    }

    /* CARD */
    .book{
        background:#fff;
        padding:10px;
        border-radius:8px;
        text-align:center;
        box-shadow:0 0 5px rgba(0,0,0,0.2);
        overflow:hidden;
    }

    .book img{
        width:200px%;
        height:250px;
        object-fit:cover;
    }

    /* BUTTON */
    .btn{
        display:block;
        margin-top:10px;
        padding:8px;
        background:#4a6cf7;
        color:#fff;
        text-decoration:none;
        border-radius:5px;
    }
</style>
</head>

<body>

<!-- HEADER -->
    <div class="header">

        <!-- LOGO + SITE NAME -->
        <div class="logo-section">
            <div class="logo">
                <img src="logo.jpeg">
            </div>
            <div class="site-name"><?php echo (!empty($_SESSION['site_name'])) ? $_SESSION['site_name'] : 'Book Spark'; ?></div>
        </div>

        <!-- RIGHT SIDE: SEARCH + CART -->
        <div class="right-section">

            <!-- SEARCH -->
            <div class="search-bar">
                <form method="GET">
                    <i class="fa fa-search"></i>
                    <input type="text" name="search" placeholder="Search by author..."
                    value="<?= $search; ?>">
                </form>
            </div>

            <!-- CART ICON -->
            <a href="Cart.php" class="cart-icon">
                <i class="fa fa-shopping-cart"></i>
                <span class="cart-count"><?= $cart_count; ?></span>
            </a>
        </div>
    </div>

<!-- MAIN -->
<div class="main">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h3>User Menu</h3>

        <a href="UserDashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a>
        <a href="UserBooks.php"><i class="fa fa-book"></i> Books</a>

        <!-- CATEGORY -->
        <div class="category-menu">
            <a href="#"><i class="fa fa-list"></i> Categories ▸</a>

            <div class="category-list">
                <a href="UserIndex.php?search=<?= $search; ?>">All</a>

                <?php while($cat = mysqli_fetch_assoc($cat_result)){ ?>
                    <a href="UserIndex.php?category=<?= $cat['CategoryId']; ?>&search=<?= $search; ?>">
                        <?= $cat['CategoryName']; ?>
                    </a>
                <?php } ?>
            </div>
        </div>

        <a href="UserFine.php"><i class="fa fa-pencil"></i> Pay Fine</a>
        <a href="UserProfile.php"><i class="fa fa-user"></i> My Profile</a>
        <a href="UserLogout.php"><i class="fa fa-sign-out"></i> Logout</a>
    </div>

    <!-- CONTENT -->
    <div class="content">
        <h2>Available Books</h2>

        <div class="books">
            <?php
                if(mysqli_num_rows($result) > 0){
                while($book = mysqli_fetch_assoc($result)){

                // IMAGE FIX
                $image = "images/default.jpg";

                if(!empty($book['BookLink'])){
                    if(filter_var($book['BookLink'], FILTER_VALIDATE_URL)){
                        $image = $book['BookLink'];
                    } else {
                        $image = "uploads/" . $book['BookLink'];
                    }
                }
            ?>

            <div class="book">
                <img src="<?= $image; ?>" 
                    style="height:150px; object-fit:contain;"
                    onerror="this.src='images/default.jpg';">
                <h4><?= $book['Title']; ?></h4>
                <p><?= $book['Author']; ?></p>

                <a href="BookDetails.php?id=<?= $book['BookId']; ?>" class="btn">
    View Details
</a>
            </div>

            <?php
            }
            }else{
                echo "<p>No books found</p>";
            }
            ?>

        </div>
    </div>
</div>
</body>
</html> 