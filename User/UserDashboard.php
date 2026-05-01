<?php
session_start();
include('Connection.php');

$user_id = $_SESSION['UserId'];

/* ===== TOTAL ISSUED BOOKS===== */
$issued_query = "SELECT COUNT(*) as total FROM IssueBook WHERE UserId='$user_id'";
$issued_result = mysqli_query($conn, $issued_query);
$totalIssued = mysqli_fetch_assoc($issued_result)['total'];

/* ===== TOTAL RETURNED BOOKS ===== */
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

/* ======= SEARCH + CATEGORY LIST ========= */
$search = isset($_GET['search']) ? $_GET['search'] : "";
$category = isset($_GET['category']) ? $_GET['category'] : "";

/*== CATEGORY LIST ==*/
$cat_query = "SELECT * FROM Category";
$cat_result = mysqli_query($conn, $cat_query);

/*===== QUERY ======*/
if($search != "" && $category != ""){
    $query = "SELECT * FROM Book 
              WHERE CategoryId='$category'
              AND (Author LIKE '%$search%' 
              OR Title LIKE '%$search%' 
              OR SOUNDEX(Author) = SOUNDEX('$search'))";
}
elseif($category != ""){
    $query = "SELECT * FROM Book WHERE CategoryId='$category'";
}
elseif($search != ""){
    $query = "SELECT * FROM Book 
              WHERE Author LIKE '%$search%' 
              OR Title LIKE '%$search%' 
              OR SOUNDEX(Author) = SOUNDEX('$search')";
}
else{
    $query = "SELECT * FROM Book";
}
$result = mysqli_query($conn, $query);

/* ===== CART FIX ===== */
$user_id = $_SESSION['UserId'];

if(!isset($_SESSION['cart'][$user_id])){
    $_SESSION['cart'][$user_id] = [];
}

if(isset($_POST['AddToCart'])){
    $id = $_POST['BookId'];

    if(!in_array($id, $_SESSION['cart'][$user_id])){
        $_SESSION['cart'][$user_id][] = $id;
    }

    echo count($_SESSION['cart'][$user_id]);
    exit();
}

$cart_count = count($_SESSION['cart'][$user_id]);
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        /*===== Full Page =====*/
        body{
            margin:0;
            font-family:Arial;
            background-color:#ffffff;
        }

        /* HEADER */
        .header{
            background:#2c3e50;
            padding:20px;
            display:flex;
            justify-content:space-between;
            align-items:center;
            color:white;
        }

        /* LOGO */
        .logo{
            display:flex;
            align-items:center;
            gap:10px;
            font-size:24px;
            font-weight:bold;
        }

        .logo img{ width:60px; }

        /* SEARCH */
        .search-box{
            background:white;
            padding:8px 35px 8px 35px; /* space for icon */
            border-radius:50px;
            display:flex;
            align-items:center;
            position:relative;
        }

        .search-icon{
            position:absolute;
            left:12px;
            color:gray;
            font-size:14px;
        }

        .search-box input{
            border:none;
            outline:none;
            width:180px;
        }

        /* CART */
        .cart {
            position: relative;
            font-size: 22px;
            margin-left: 15px;
        }

        .cart span {
            position: absolute;
            top: -5px;
            right: -6px;
            background: #ff3b3b;
            color: white;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 20px;
            font-weight: bold;
        }

        .user-menu {
            position: relative;
        }

        .dropdown-menu-custom {
            display: none;
            position: absolute;
            right: 0;
            top: 40px;
            background: white;
            min-width: 150px;
            border-radius: 8px;
            box-shadow: 0px 5px 10px rgba(0,0,0,0.2);
            z-index: 1000;
        }

        .dropdown-menu-custom a {
            display: block;
            padding: 10px;
            color: #333;
            text-decoration: none;
        }

        .dropdown-menu-custom a:hover {
            background: #1abc9c;
            color: white;
        }
        /* SIDEBAR */
        .sidebar{
            width:220px;
            background:#34495e;
            min-height:100vh;
            padding:20px;
        }

        .sidebar a{
            display:block;
            color:white;
            padding:10px;
            margin-bottom:10px;
            background:#3d566e;
            text-decoration:none;
            border-radius:5px;
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
            position: relative;
        }

        .category-menu > a{
            display:block;
            color:white;
            padding:10px;
            margin-bottom:10px;
            background:#3d566e;
            border-radius:5px;
            text-decoration:none;
        }

        /* HIDE LIST */
        .category-list{
            display:none;
            position:absolute;
            left:100%;
            top:0;
            background:white;
            min-width:180px;
            border-radius:5px;
            z-index:1000;
        }

        /* SHOW ON HOVER */
        .category-menu:hover .category-list{
            display:block;
        }

        /* STYLE LINKS */
        .category-list a{
            color:#333;
            padding:10px;
            display:block;
            text-decoration:none;
        }

        .category-list a:hover{
            background:#1abc9c;
            color:white;
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
            height: 190px; /* 👈 fixed height */
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

        /* FOOTER */
        .footer {
            background-color: #0d3b4c;
            padding: 10px;
            text-align: center;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <div class="header">
        <div class="logo">
            <img src="logo.jpeg">
            <h5><?php echo (!empty($_SESSION['site_name'])) ? $_SESSION['site_name'] : 'Book Spark'; ?></h5>
        </div>

        <!-- SEARCH -->
        <div class="d-flex align-items-center">
            <form method="GET" class="search-box position-relative">
                <i class="fa fa-search search-icon"></i>
                <input type="text" name="search" placeholder="Search author..." value="<?= $search ?>">
            </form>

            <!-- CART -->
            <a href="Cart.php" class="cart">
                <i class="fa fa-shopping-cart"></i>
                <span class="cart-count"><?= $cart_count ?></span>
            </a>

            <!-- USER PROFILE -->
            <div class="user-menu ms-3">
                <i class="fa fa-user-circle fa-2x text-white" style="cursor:pointer;" onclick="toggleMenu()"></i>

                <div id="userDropdown" class="dropdown-menu-custom">
                    <a href="UserProfile.php">My Profile</a>
                    <a href="UserLogout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex">

        <!-- SIDEBAR -->
        <div class="sidebar">
            <a href="UserDashboard.php"><i class="fa fa-dashboard"></i>Dashboard</a>
            <a href="UserBooks.php"><i class="fa fa-book"></i>Books</a>
            <div class="category-menu">
                <a href="#"><i class="fa fa-tags"></i> Categories ▸</a>

                <div class="category-list">
                    <a href="UserBooks.php">All</a>

                    <?php while($cat = mysqli_fetch_assoc($cat_result)){ ?>
                        <a href="UserBooks.php?category=<?= $cat['CategoryId']; ?>">
                            <?= $cat['CategoryName']; ?>
                        </a>
                    <?php } ?>
                </div>
            </div>
            <a href="UserFine.php"><i class="fa fa-pencil"></i>Pay Fine</a>
            <a href="UserLogout.php"><i class="fa fa-sign-out"></i>Logout</a>
        </div>
        
        <!-- Main Content -->
        <div class="container-fluid p-4">

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

    <!-- FOOTER -->
    <footer class="footer mt-5">
        <div class="container">
        <div class="row text-center justify-content-center">
                <!-- ABOUT -->
                <div class="col-md-6 mb-3">
                    <h5 class="text-white">About</h5>
                    <p class="text-white-50">
                        An advanced digital library platform that simplifies book management,
                        improves accessibility, and enhances user experience.
                    </p>
                </div>

                <!-- CONTACT -->
                <div class="col-md-6 mb-3">
                    <h5 class="text-white">Contact</h5>
                    <p class="text-white-50 mb-1">
                        <i class="fas fa-envelope me-2"></i> booksparkgmail.com
                    </p>
                    <p class="text-white-50 mb-1">
                        <i class="fas fa-phone me-2"></i> +91 98765 43210
                    </p>
                    <p class="text-white-50 mb-1">
                        <i class="fab fa-instagram me-2"></i> Book_Spark
                    </p>
                    <p class="text-white-50">
                        <i class="fas fa-clock me-2"></i> 9 AM - 6 PM
                    </p>
                </div>

            </div>

            <hr style="border-color: rgba(255,255,255,0.2);">

            <!-- COPYRIGHT -->
            <div class="text-center text-white-50 py-2">
                © <?= date("Y") ?> BookSpark Library Management System. All rights reserved.
            </div>
        </div>
    </footer>
    <script>
    function toggleMenu() {
        let menu = document.getElementById("userDropdown");
        menu.style.display = (menu.style.display === "block") ? "none" : "block";
    }

    // Optional: close when clicking outside
    document.addEventListener("click", function(e){
        let menu = document.getElementById("userDropdown");
        if(!e.target.closest('.user-menu')){
            menu.style.display = "none";
        }
    });
</script>
</body>
</html>