<?php
session_start();

/* DEFAULT VALUES (if not set) */
if(!isset($_SESSION['site_name'])) $_SESSION['site_name'] = "Book Spark";
if(!isset($_SESSION['fine_per_day'])) $_SESSION['fine_per_day'] = 10;
if(!isset($_SESSION['borrow_days'])) $_SESSION['borrow_days'] = 7;

/* SAVE SETTINGS */
if(isset($_POST['save'])){
    $_SESSION['site_name'] = $_POST['site_name'];
    $_SESSION['fine_per_day'] = $_POST['fine_per_day'];
    $_SESSION['borrow_days'] = $_POST['borrow_days'];

    $msg = "Settings Updated Successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Setting</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body {
    margin: 0; 
    background:#f5f5f5; 
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
    display: flex;
    align-items: center;
    padding: 12px;
    text-decoration: none;
}

.sidebar a:hover {
    background: #145a6f;
}

.sidebar a i {
    width: 20px;
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
    display: block;
    margin: 0 auto; /* centers image horizontally */
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

.settings-box {
    max-width: 500px;
    margin: auto;
    margin-top: 80px;
    padding: 25px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
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

    <div class="logo">
        <a href="index.php">
            <img src="logo.jpeg">
        </a>
        <h5><?php echo (!empty($_SESSION['site_name'])) ? $_SESSION['site_name'] : 'Book Spark'; ?></h5>
    </div>

    <a href="AdminIndex.php"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
    <a href="AdminManageBook.php"><i class="fas fa-book me-2"></i> Manage Book</a>
    <a href="AdminManageUser.php"><i class="fas fa-users me-2"></i> Manage User</a>
    <a href="AdminRequestBook.php"><i class="fas fa-book-open me-2"></i> Requested Book</a>
    <a href="AdminIssueBook.php"><i class="fas fa-arrow-up me-2"></i> Issued Books</a>
    <a href="AdminReturnBook.php"><i class="fas fa-arrow-down me-2"></i> Return Book</a>
    <a href="AdminFine.php"><i class="fas fa-money-bill-wave me-2"></i> Fine</a>
    <a href="AdminSettings.php" class="<?php if(basename($_SERVER['PHP_SELF'])=='AdminSettings.php') echo 'active'; ?>"><i class="fas fa-cog me-2"></i> Settings</a>
    <a href="AdminLogout.php" ><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
</div>

<!-- Main -->
<div class="main">

    <div class="header mb-4">
        <h4>Librarian Settings Panel</h4>
    </div>

    <div class="settings-box" style="margin-bottom: 100px;">

        <h4 class="text-center mb-4">Admin Settings</h4>

        <?php if(isset($msg)){ ?>
            <div class="alert alert-success"><?php echo $msg; ?></div>
        <?php } ?>

        <form method="post">

            <div class="mb-3">
                <label>Website Name</label>
                <input type="text" name="site_name" class="form-control"
                    value="<?php echo $_SESSION['site_name']; ?>">
            </div>

            <div class="mb-3">
                <label>Fine Per Day (₹)</label>
                <input type="number" name="fine_per_day" class="form-control"
                    value="<?php echo $_SESSION['fine_per_day']; ?>">
            </div>

            <div class="mb-3">
                <label>Borrow Period (Days)</label>
                <input type="number" name="borrow_days" class="form-control"
                    value="<?php echo $_SESSION['borrow_days']; ?>">
            </div>

            <button type="submit" name="save" class="btn btn-primary w-100">
                Save Settings
            </button>

        </form>

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