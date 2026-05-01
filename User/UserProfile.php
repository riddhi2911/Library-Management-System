<?php
session_start();
include("Connection.php");

$user_id = $_SESSION['UserId'];

$query = "SELECT * FROM Users WHERE UserId='$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Profile Image Logic
$image = "images/default-user.png";

if(!empty($user['profile_image'])){
    $image = $user['profile_image']; // OR "uploads/".$user['profile_image']
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Profile</title>

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

        /* Profile Card */
        .profile-card {
            max-width: 600px;
            margin: 60px auto;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            background: white;
            overflow: hidden;
        }

        /* Header */
        .profile-header {
            background: #2c3e50;
            color: white;
            text-align: center;
            padding: 30px 20px;
        }

        /* Profile Image */
        .profile-header img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid white;
            margin-bottom: 10px;
        }

        /* Body */
        .profile-body {
            padding: 25px;
        }

        .profile-body p {
            font-size: 15px;
            margin-bottom: 10px;
        }

        /* Buttons */
        .profile-actions {
            text-align: center;
            padding-bottom: 20px;
        }

        .profile-actions a {
            margin: 5px;
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
    </div>

    <div class="profile-card">

        <!-- HEADER -->
        <div class="profile-header">
            <img src="<?= $image ?>" onerror="this.src='images/default-user.png';">
            <h4><?= $user['Name'] ?></h4>
            <p><?= $user['Email'] ?></p>
        </div>

        <!-- BODY -->
        <div class="profile-body">

            <p><strong><i class="fa fa-user me-2"></i>Name:</strong> <?= $user['Name'] ?></p>

            <p><strong><i class="fa fa-envelope me-2"></i>Email:</strong> <?= $user['Email'] ?></p>

            <p><strong><i class="fa fa-phone me-2"></i>Phone:</strong> <?= $user['PhoneNo'] ?></p>

            <p><strong><i class="fa fa-map-marker-alt me-2"></i>Address:</strong> <?= $user['Address'] ?></p>

            <p><strong><i class="fa fa-city me-2"></i>City:</strong> <?= $user['City'] ?></p>

            <p><strong><i class="fa fa-calendar me-2"></i>Date of Birth:</strong> <?= $user['DOB'] ?></p>

        </div>

        <!-- ACTIONS -->
        <div class="profile-actions">
            <a href="EditProfile.php" class="btn btn-primary">
                <i class="fa fa-edit"></i> Edit Profile
            </a>

            <a href="UserDashboard.php" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Back
            </a>
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
</body>
</html>