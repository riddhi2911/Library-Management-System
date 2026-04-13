<?php
session_start();
include('Connection.php');

//check login
if(!isset($_SESSION['UserId']))
{
    header("Location: UserLogin.php");
    exit();
}

//fetch only login user's data
$userId = $_SESSION['UserId'];

$query = "SELECT * FROM Users WHERE UserId = '$userId'";
$result = mysqli_query($conn,$query);
$user = mysqli_fetch_assoc($result);

// Fetch categories
$cat_query = "SELECT * FROM Categories";
$cat_result = mysqli_query($conn, $cat_query);

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
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<title>User Home | Library System</title>

<style>
body{
    margin:0;
    font-family:Arial;
    background-color:#ffffff;
}

/* HEADER */
.header {
    display:flex;
    justify-content:space-between;
    background-color:#2c3e50;
    color:white;
    padding:30px 40px;
}

.logo-section {
    display:flex;
    align-items:center;
    gap:35px;
}

.logo img{
    width:70px;
}

.site-name {
    font-size:28px;
    font-weight:bold;
}

.right-section {
    display:flex;
    align-items:center;
    gap:10px;
}

.cart-icon {
    position:relative;
    color:white;
    font-size:25px;
    text-decoration:none;
}

.cart-count {
    position:absolute;
    top:-8px;
    right:-10px;
    background:red;
    color:white;
    font-size:12px;
    padding:3px 6px;
    border-radius:50%;
}

/* MAIN */
.main {
    display:flex;
    height:calc(100vh - 70px);
}

/* SIDEBAR */
.sidebar {
    width:250px;
    background:#34495e;
    color:white;
    padding:20px;
}

.sidebar h3 {
    text-align:center;
}

.sidebar a {
    display:block;
    padding:10px;
    margin-bottom:10px;
    background:#3d566e;
    color:white;
    text-decoration:none;
    border-radius:5px;
}

.sidebar a:hover {
    background:#1abc9c;
}

/* CATEGORY */
.category-menu {
    position:relative;
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

/* CONTENT */
.content {
    flex:1;
    display:flex;
    justify-content:center;
    align-items:center;
}

/* PROFILE CARD */
.profile-card {
    background:#fff;
    width:420px;
    padding:35px;
    border-radius:18px;
    text-align:center;
    box-shadow:0 10px 25px rgba(0,0,0,0.12);
}

.profile-img {
    width:140px;
    height:140px;
    border-radius:50%;
    overflow:hidden;
    margin:auto;
    border:4px solid #1abc9c;
}

.profile-img img {
    width:100%;
    height:100%;
    object-fit:cover;
}

.profile-name {
    margin:15px 0 25px;
    font-size:26px;
    color:#2c3e50;
}

.profile-info .row {
    display:flex;
    justify-content:space-between;
    padding:12px 0;
    border-bottom:1px solid #eee;
}

.profile-info .row span:first-child {
    font-weight:600;
    color:#7f8c8d;
}

.profile-info .row span:last-child {
    color:#2c3e50;
}

.update-btn {
    display:inline-block;
    margin-top:30px;
    padding:12px 35px;
    background:#1abc9c;
    color:#fff;
    border-radius:30px;
    text-decoration:none;
    font-weight:bold;
}

.update-btn:hover {
    background:#16a085;
}
</style>
</head>

<body>

<!-- HEADER -->
<div class="header">

    <div class="logo-section">
        <div class="logo">
            <img src="logo.jpeg">
        </div>
        <div class="site-name">
            <?php echo (!empty($_SESSION['site_name'])) ? $_SESSION['site_name'] : 'Book Spark'; ?>
        </div>
    </div>

    <div class="right-section">
        <a href="Cart.php" class="cart-icon">
            <i class="fa fa-shopping-cart"></i>
            <span class="cart-count"><?= $cart_count; ?></span>
        </a>
    </div>
</div>

<div class="main">

<!-- SIDEBAR -->
<div class="sidebar">
    <h3>User Menu</h3>

    <a href="UserDashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a>
    <a href="UserBooks.php"><i class="fa fa-book"></i> Books</a>

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

<!-- CONTENT -->
<div class="content">

<div class="profile-card">

    <!-- FIXED IMAGE LOGIC -->
    <div class="profile-img">
       <?php
        $img = $user['profile_image'];

        // remove "uploads/" if already stored in DB
        $img = str_replace("uploads/", "", $img);

        if(!empty($img) && file_exists("uploads/".$img)){
            $final_img = "uploads/".$img;
        }else{
            $final_img = "uploads/default.png";
        }
        ?>

        <img src="<?php echo $final_img; ?>" alt="Profile Image">
    </div>

    <h2 class="profile-name"><?php echo $user['Name']; ?></h2>

    <div class="profile-info">
        <div class="row"><span>Email</span><span><?php echo $user['Email']; ?></span></div>
        <div class="row"><span>Phone</span><span><?php echo $user['PhoneNo']; ?></span></div>
        <div class="row"><span>Address</span><span><?php echo $user['Address']; ?></span></div>
        <div class="row"><span>City</span><span><?php echo $user['City']; ?></span></div>
        <div class="row"><span>Birthdate</span><span><?php echo $user['DOB']; ?></span></div>
    </div>

    <a href="EditProfile.php" class="update-btn">
        <i class="fa fa-edit"></i> Update Profile
    </a>

</div>

</div>

</div>

</body>
</html>