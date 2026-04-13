<?php
session_start();
include("Connection.php");

/* COUNTS */
$members = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users"))['total'];
$issued_books = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM IssueBook WHERE Status='Issued'"))['total'];
$books = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM Book"))['total'];
$fine = mysqli_fetch_assoc(mysqli_query($conn, "SELECT IFNULL(SUM(FineAmount),0) AS total FROM Fine"))['total'];

$total_issued = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM IssueBook"))['total'];
$total_returned = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM IssueBook WHERE Status='Returned'"))['total'];
$not_returned = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM IssueBook WHERE Status='Issued'"))['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

/* Sidebar */
.sidebar {
    width: 250px;
    height: 100vh;
    background: #0d3b4c;
    color: white;
    position: fixed;
    display: flex;
    flex-direction: column;
}

/* Logo */
.logo {
    text-align: center;
    padding: 20px;
    flex-shrink: 0;
}

.logo img {
    width: 80px;
}

/* Scroll links */
.menu-links {
    flex-grow: 1;
    overflow-y: auto;
}

/* Links */
.menu-links a {
    color: white;
    display: flex;
    align-items: center;
    padding: 12px;
    text-decoration: none;
}

/* Hover */
.menu-links a:hover {
    background: #145a6f;
}

/* ACTIVE LINK */
.menu-links a.active {
    background: #145a6f;
    font-weight: bold;
    border-left: 4px solid #fff;
}

/* Main */
.main {
    margin-left: 250px;
    padding: 20px;
}

/* Header */
.header {
    background: #145a6f;
    color: white;
    padding: 15px;
    border-radius: 5px;
}

/* Cards */
.card-box {
    color: white;
    border-radius: 10px;
    overflow: hidden;
}

.card-footer-custom {
    background: rgba(255,255,255,0.2);
    padding: 10px;
}

.card-footer-custom a {
    text-decoration: none;
}

/* Extra cards */
.bg-orange { background: linear-gradient(45deg,#ff9f43,#ff6b6b); }
.bg-blue { background: linear-gradient(45deg,#4facfe,#00f2fe); }
.bg-red { background: linear-gradient(45deg,#ff4b2b,#ff416c); }

.footer {
    background-color: #0d3b4c;
    padding: 10px;
    text-align: center;
}

</style>
</head>

<body>

<!-- Sidebar -->
<div class="sidebar">

    <div class="logo">
        <img src="logo.jpeg">
        <h5><?php echo (!empty($_SESSION['site_name'])) ? $_SESSION['site_name'] : 'Book Spark'; ?></h5>
    </div>

    <div class="menu-links">

    <a href="AdminIndex.php" class="<?php if(basename($_SERVER['PHP_SELF'])=='AdminIndex.php') echo 'active'; ?>"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
    <a href="AdminManageBook.php"><i class="fas fa-book me-2"></i> Manage Book</a>
    <a href="AdminManageUser.php"><i class="fas fa-users me-2"></i> Manage User</a>
    <a href="AdminRequestBook.php"><i class="fas fa-book-open me-2"></i> Requested Book</a>
    <a href="AdminIssueBook.php"><i class="fas fa-arrow-up me-2"></i> Issued Books</a>
    <a href="AdminReturnBook.php"><i class="fas fa-arrow-down me-2"></i> Return Book</a>
    <a href="AdminFine.php"><i class="fas fa-money-bill-wave me-2"></i> Fine</a>
    <a href="AdminSettings.php"><i class="fas fa-cog me-2"></i> Settings</a>
    <a href="AdminLogout.php" ><i class="fas fa-sign-out-alt me-2"></i> Logout</a>

    </div>

</div>

<!-- Main -->
<div class="main">

    <div class="header mb-4">
        <h4>Librarian Control Panel</h4>
    </div>

    <!-- Main Cards -->
    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="card-box bg-primary">
                <div class="p-3">
                    <h3><?php echo $members; ?></h3>
                    Members
                </div>
                <div class="card-footer-custom d-flex justify-content-between">
                    <a href="AdminManageUser.php" class="text-white">View More</a>
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-box bg-success">
                <div class="p-3">
                    <h3><?php echo $issued_books; ?></h3>
                    Issued Books
                </div>
                <div class="card-footer-custom d-flex justify-content-between">
                    <a href="AdminIssueBook.php" class="text-white">View More</a>
                    <i class="fas fa-book"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-box bg-danger">
                <div class="p-3">
                    <h3><?php echo $books; ?></h3>
                    Books
                </div>
                <div class="card-footer-custom d-flex justify-content-between">
                    <a href="AdminManageBook.php" class="text-white">View More</a>
                    <i class="fas fa-book-open"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-box bg-warning">
                <div class="p-3">
                    <h3>₹<?php echo $fine; ?></h3>
                    Fine
                </div>
                <div class="card-footer-custom d-flex justify-content-between">
                    <a href="AdminFine.php" class="text-white">View More</a>
                    <i class="fas fa-money-bill-wave"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- Extra Cards -->
    <div class="row g-3" style="margin-bottom: 100px;">

        <div class="col-md-4">
            <div class="card-box bg-orange text-center p-4">
                <h3><?php echo $total_issued; ?></h3>
                Total Issued Books
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-box bg-blue text-center p-4">
                <h3><?php echo $total_returned; ?></h3>
                Total Returned Books
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-box bg-red text-center p-4">
                <h3><?php echo $not_returned; ?></h3>
                Non-Returned Books
            </div>
        </div>

    </div>

     <footer class="footer mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5 class="text-white">About</h5>
                    <p class="text-white-50">An advanced digital library platform that simplifies book management, improves accessibility, and enhances user experience.</p>
                </div>
                <div class="col-md-4">
                    <h5 class="text-white">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="AdminIndex.php" class="text-white-50 text-decoration-none">Dashboard</a></li>
                        <li><a href="AdminManageBook.php" class="text-white-50 text-decoration-none">Books</a></li>
                        <li><a href="AdminManageUser.php" class="text-white-50 text-decoration-none">Users</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5 class="text-white">Contact</h5>
                    <p class="text-white-50">
                        <i class="fas fa-envelope me-2"></i>booksparkgmail.com<br>
                        <i class="fas fa-phone me-2"></i> +91 98765 43210<br>
                        <i class="fab fa-instagram me-2"></i> 
                        <i class="text-white text-decoration-none"></i>Book_Spark <br>
                        <i class="fas fa-clock me-2"></i> 9 AM - 6 PM
                    </p>
                </div>
            </div>
            <hr class="bg-white">
            <div class="text-center text-white-50 py-2">
                <p><?php echo date("Y"); ?> BookSpark Library Management System. All rights reserved.</p>
            </div>
        </div>
    </footer>
</div>

</body>
</html>