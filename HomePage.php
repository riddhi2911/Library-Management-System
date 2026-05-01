<?php
$servername = "localhost:3306";
$username = "root";
$password = "";
$dbname = "LibraryManagement";

$conn = mysqli_connect($servername,$username,$password,$dbname);

// Fetch counts
$userResult = mysqli_query($conn, "SELECT COUNT(*) as total FROM users");
$bookResult = mysqli_query($conn, "SELECT COUNT(*) as total FROM Book");
$tranResult = mysqli_query($conn, "SELECT COUNT(*) as total FROM IssueBook");

$userCount = mysqli_fetch_assoc($userResult)['total'];
$bookCount = mysqli_fetch_assoc($bookResult)['total'];
$tranCount = mysqli_fetch_assoc($tranResult)['total'];

function formatCount($num) {
    if ($num >= 1000) return floor($num/1000) . "K+";
    if ($num > 0) return $num . "+";
    return $num;
}

$bookQuery = mysqli_query($conn, "SELECT Title, Author, Rate, BookLink FROM Book ORDER BY Rate DESC LIMIT 6");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>BookSpark Library</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<style>
body {
    font-family: Arial;
    background: #f8fafc;
}

/* HEADER */
.header {
    background: #2c3e50;
    color: white;
    padding: 20px 60px;
}

/* MAIN */
.main-content {
    padding: 60px 20px;
}

/* STATS */
.stat h2 {
    color: #1abc9c;
    font-weight: bold;
}

/* CAROUSEL */
.carousel img {
    height: 300px;
    object-fit: cover;
    border-radius: 10px;
}

/* MODERN BOOK SECTION */
.books-section {
    background: #e2e8f0;
    border-radius: 18px;
    padding: 50px 30px;
    margin-top: 50px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.05);
}

/* TITLE STYLE */
.section-title {
    font-size: 32px;
    font-weight: 700;
    color: #2c3e50;
    position: relative;
    display: inline-block;
}

.section-title::after {
    content: "";
    display: block;
    width: 60px;
    height: 4px;
    background: #1abc9c;
    margin: 8px auto 0;
    border-radius: 2px;
}

/* CARD SPACING */
.book-card {
    padding: 12px;
}

/* CARD DESIGN */
.card {
    height: 240px;
    border-radius: 14px;
    border: none;
    background: #ffffff;
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    position: relative;
}

/* HOVER EFFECT */
.card:hover {
    transform: translateY(-6px) scale(1.02);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

/* BADGE */
.badge-top {
    position: absolute;
    top: 8px;
    left: 8px;
    background: #1abc9c;
    color: white;
    font-size: 11px;
    padding: 4px 8px;
    border-radius: 6px;
}

/* IMAGE */
.book-img {
    height: 110px;
    object-fit: contain;
    padding: 5px;
}

/* TEXT */
.card-body {
    padding: 8px;
}

.card-title {
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 4px;
}

.card-text {
    font-size: 12px;
    margin-bottom: 4px;
}

/* RATING */
.rating i {
    font-size: 12px;
}

/* FOOTER */
.footer {
    background-color: #2c3e50;
    padding: 10px;
    text-align: center;
}
</style>
</head>

<body>

<!-- HEADER -->
<div class="header d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center">
        <img src="Admin/logo.jpeg" style="width:70px; margin-right:15px;">       
        <h1 class="m-0 fw-bold">BookSpark</h1>
    </div>

    <div>
        <a href="User/UserHomepage.php" class="btn btn-success btn-lg me-2">User</a>
        <a href="Admin/AdminLogin.php" class="btn btn-success btn-lg">Admin</a>
    </div>
</div>

<!-- MAIN -->
<div class="container main-content">
    <div class="row align-items-center">

        <div class="col-md-6">
            <h1>Library Management System</h1>
            <p>
                BookSpark Library Management System is a smart and efficient digital platform
                designed to simplify book management, user handling, and issue-return tracking.
            </p>

            <div class="row text-center mt-4">
                <div class="col">
                    <div class="stat">
                        <h2><?php echo formatCount($userCount); ?></h2>
                        <p>Users</p>
                    </div>
                </div>

                <div class="col">
                    <div class="stat">
                        <h2><?php echo formatCount($bookCount); ?></h2>
                        <p>Books</p>
                    </div>
                </div>

                <div class="col">
                    <div class="stat">
                        <h2><?php echo formatCount($tranCount); ?></h2>
                        <p>Transactions</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- CAROUSEL -->
        <div class="col-md-6">
        <div id="libraryCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="2000">
                <div class="carousel-inner">

                    <div class="carousel-item active">
                        <img src="https://www.shcollege.ac.in/wp-content/uploads/Images/admin_panel/Pages/Library-7.jpg" class="d-block w-100">
                    </div>

                    <div class="carousel-item">
                        <img src="https://dpcoepune.edu.in/wp-content/uploads/2024/07/DSC00841-1-scaled.jpg" class="d-block w-100">
                    </div>

                    <div class="carousel-item">
                        <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794" class="d-block w-100">
                    </div>

                    <div class="carousel-item">
                        <img src="https://nerist.ac.in/wp-content/uploads/2024/07/library-3-1-1024x680.jpg" class="d-block w-100">
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

<!-- BOOK SECTION -->
<div class="container books-section text-center">
    <h2 class="section-title mb-4">Top Rated Books</h2>

    <div class="row justify-content-center">
        <?php while($book = mysqli_fetch_assoc($bookQuery)) { ?>
            
            <div class="col-lg-2 col-md-3 col-sm-4 col-6 book-card">
                <div class="card">

                    <span class="badge-top">Top</span>

                    <img src="<?php echo $book['BookLink']; ?>" class="card-img-top book-img">

                    <div class="card-body">
                        <h5 class="card-title"><?php echo $book['Title']; ?></h5>

                        <p class="card-text">
                            <?php echo $book['Author']; ?>
                        </p>

                        <div class="rating">
                            <?php
                                $rating = round($book['Rate']);
                                for($i=1; $i<=5; $i++) {
                                    echo $i <= $rating
                                        ? '<i class="fas fa-star text-warning"></i>'
                                        : '<i class="far fa-star text-warning"></i>';
                                }
                            ?>
                            <span>(<?php echo $book['Rate']; ?>)</span>
                        </div>

                    </div>
                </div>
            </div>

        <?php } ?>
    </div>
</div>

<!-- FOOTER -->
<footer class="footer mt-5">
    <div class="container">
        <div class="row">

            <div class="col-md-4">
                <h5 class="text-white">About</h5>
                <p class="text-white-50">An advanced digital library platform that simplifies book management.</p>
            </div>

            <div class="col-md-4">
                <h5 class="text-white">Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="User/UserHomepage.php" class="text-white-50 text-decoration-none">Login</a></li>
                    <li><a href="User/UserDashboard.php" class="text-white-50 text-decoration-none">Dashboard</a></li>
                    <li><a href="User/UserBooks.php" class="text-white-50 text-decoration-none">Books</a></li>
                </ul>
            </div>

            <div class="col-md-4">
                <h5 class="text-white">Contact</h5>
                <p class="text-white-50">
                    <i class="fas fa-envelope me-2"></i>booksparkgmail.com<br>
                    <i class="fas fa-phone me-2"></i> +91 98765 43210<br>
                    <i class="fab fa-instagram me-2"></i>Book_Spark <br>
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

</body>
</html>