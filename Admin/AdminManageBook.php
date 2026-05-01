<?php
include('connection.php');
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Book</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body {
    margin: 0;
}

/* Sidebar */
.sidebar {
    width: 250px;
    min-height: 100vh;
    background: #0d3b4c;
    color: white;
    position: fixed;
}

.sidebar a {
    color: white;
    display: block;
    padding: 12px;
    text-decoration: none;
}

.sidebar a:hover {
    background: #145a6f;
}

.sidebar a.active {
    background: #145a6f;
    color: white;
    font-weight: bold;
    border-left: 4px solid #fff;
}
/* Logo */
.logo {
    text-align: center;
    padding: 20px;
}

.logo img {
    width: 80px;
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

.book-card {
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    transition: 0.3s;
}

.book-card {
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    transition: 0.3s;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.card-body {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.card-img-top {
    height: 150px;
    object-fit: contain;
}

.book-card:hover {
    transform: translateY(-5px);
}

.new-card {
    height: 100%;
    min-height: 300px;
    border: 2px dashed #ccc;
    color: #555;
    transition: 0.3s;
    cursor: pointer;
}

.new-card:hover {
    background: #f8f9fa;
    border-color: #145a6f;
    color: #145a6f;
}

.star {
    font-size: 16px;
}

.available {
    color: green;
    font-size: 14px;
    font-weight: 500;
}

.not-available {
    color: red;
    font-size: 14px;
    font-weight: 500;
}

.category-badge {
    background: #e9ecef;
    padding: 4px 8px;
    border-radius: 8px;
    font-size: 12px;
}

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

    <!-- LOGO -->
    <div class="logo">
        <a href="index.php">
            <img src="logo.jpeg" alt="Logo">
        </a>
        <h5><?php echo (!empty($_SESSION['site_name'])) ? $_SESSION['site_name'] : 'Book Spark'; ?></h5>
    </div>

    <a href="AdminIndex.php"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
    <a href="AdminManageBook.php" class="<?php if(basename($_SERVER['PHP_SELF'])=='AdminManageBook.php') echo 'active'; ?>"><i class="fas fa-book me-2"></i> Manage Book</a>
    <a href="AdminManageUser.php"><i class="fas fa-users me-2"></i> Manage User</a>
    <a href="AdminRequestBook.php"><i class="fas fa-book-open me-2"></i> Requested Book</a>
    <a href="AdminIssueBook.php"><i class="fas fa-arrow-up me-2"></i> Issued Books</a>
    <a href="AdminReturnBook.php"><i class="fas fa-arrow-down me-2"></i> Return Book</a>
    <a href="AdminFine.php"><i class="fas fa-money-bill-wave me-2"></i> Fine</a>
    <a href="AdminSettings.php"><i class="fas fa-cog me-2"></i> Settings</a>
    <a href="AdminLogout.php" ><i class="fas fa-sign-out-alt me-2"></i> Logout</a>

</div>

    <!-- Main -->
    <div class="main">

        <!-- Header -->
        <div class="header mb-4">
            <h4>Librarian Manage Book Panel</h4>
        </div>

        <form method="GET" class="mb-4">
            <div class="row">

                <div class="col-md-10">
                    <input type="text" name="search" class="form-control" 
                        placeholder="Search by Title, Author or Category..."
                        value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Search</button>
                </div>

            </div>
        </form>

        <div class="container-fluid" style="margin-bottom: 100px;">
            <div class="row">

                <div class="col-md-12">
                    
                    <div class="row">

                        <div class="col-md-3 mb-4">
                            <a href="AddBook.php" style="text-decoration:none;">
                                <div class="card book-card d-flex align-items-center justify-content-center new-card">

                                    <div class="text-center">
                                        <h1 style="font-size:50px;">+</h1>
                                        <p>Add New Book</p>
                                    </div>

                                </div>
                            </a>
                        </div>
                        
                        <?php
                        $search = "";

                        if(isset($_GET['search'])) {
                            $search = mysqli_real_escape_string($conn, $_GET['search']);
                        }

                        $query = "SELECT Book.*, Category.CategoryName 
                                FROM Book 
                                JOIN Category ON Book.CategoryId = Category.CategoryId";

                        if(!empty($search)) {
                            $query .= " WHERE 
                                Book.Title LIKE '%$search%' OR
                                Book.Author LIKE '%$search%' OR
                                Category.CategoryName LIKE '%$search%'";
                        }

                        $result = mysqli_query($conn, $query);

                        if(mysqli_num_rows($result) > 0) {
                            while($row = mysqli_fetch_assoc($result)) {
                        ?>

                        <div class="col-md-3 mb-4">
                            <div class="card book-card p-2">

                                <!-- Book Image -->
                                <img src="<?php echo $row['BookLink']; ?>" class="card-img-top" style="height:150px; object-fit:contain;">

                                <div class="card-body">
                                    <h6 class="card-title"><?php echo $row['Title']; ?></h6>

                                    <p class="text-muted mb-1"><?php echo $row['Author']; ?></p>

                                    <!-- Star -->
                                    <p class="mb-1">
                                        <?php
                                        $rating = isset($row['Rate']) ? $row['Rate'] : 0;

                                        $full = floor($rating);        // full stars
                                        $half = ($rating - $full >= 0.5) ? 1 : 0;
                                        $empty = 5 - ($full + $half);

                                        // Full stars
                                        for($i = 0; $i < $full; $i++){
                                            echo "<span style='color:gold;'>★</span>";
                                        }

                                        // Half star
                                        if($half){
                                            echo "<span style='color:gold;'>⯨</span>"; // half star symbol
                                        }

                                        // Empty stars
                                        for($i = 0; $i < $empty; $i++){
                                            echo "<span style='color:#ccc;'>☆</span>";
                                        }
                                        echo " <small>(" . $rating . "/5)</small>";

                                        ?>
                                    </p>

                                    <!-- Availability -->
                                    <p class="<?php echo ($row['Quantity'] > 0) ? 'available' : 'not-available'; ?>">
                                        ● <?php echo ($row['Quantity'] > 0) 
                                            ? "Available (".$row['Quantity'].")" 
                                            : "Not Available"; ?>
                                    </p>

                                    <div class="row">
                                        <!-- Update Button -->
                                        <div class="col-md-6 mb-3">
                                            <a href="EditBook.php?id=<?php echo $row['BookId']; ?>" 
                                            class="btn btn-sm btn-primary">
                                                Edit
                                            </a>
                                        </div>

                                        <!-- Delete Button -->
                                        <div class="col-md-6 mb-3">
                                            <a href="DeleteBook.php?id=<?php echo $row['BookId']; ?>" 
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this book?');">
                                                Delete
                                            </a>
                                        </div>
                                    </div>
                                    

                                    <!-- Category -->
                                    <span class="category-badge">
                                        <?php echo $row['CategoryName']; ?>
                                    </span>
                                </div>

                            </div>
                        </div>

                        <?php }
                        } else {
                        ?>
                            <!-- No Book Found -->
                            <div class="col-12 text-center mt-4">
                                <div class="alert alert-warning">
                                    No Book Found for your search
                                </div>
                            </div>

                        <?php } ?>
                    
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
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
                        <li><a href="AdminManageBook.php" class="text-white-50 text-decoration-none">Manage Books</a></li>
                        <li><a href="AdminManageUser.php" class="text-white-50 text-decoration-none">Manage Users</a></li>
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

</body>
</html>


