<?php
session_start();
include("Connection.php");

$user_id = $_SESSION['UserId'];

/* FETCH USER */
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM Users WHERE UserId='$user_id'"));

/* UPDATE */
if(isset($_POST['update'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $dob = $_POST['dob'];

    // IMAGE UPLOAD
    $imageName = $user['ProfileImage'];

    if(!empty($_FILES['image']['name'])){
        $imageName = time() . "_" . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $imageName);
    }

    mysqli_query($conn, "UPDATE Users SET 
        Name='$name',
        Email='$email',
        PhoneNo='$phone',
        Address='$address',
        City='$city',
        DOB='$dob',
        profile_image='$imageName'
        WHERE UserId='$user_id'
    ");

    header("Location: UserProfile.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Profile</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

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

    /* Card */
    .profile-card {
        max-width: 600px;
        margin: 50px auto;
        background: white;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        padding: 25px;
    }

    /* Image Preview */
    .profile-img {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        display: block;
        margin: 0 auto 15px;
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
            BookSpark
        </div>
    </div>

    <div class="profile-card">

        <h3 class="text-center mb-4">Edit Profile</h3>

        <!-- IMAGE -->
        <?php 
        $img = !empty($user['ProfileImage']) ? "uploads/".$user['ProfileImage'] : "images/default-user.png";
        ?>
        <img src="<?= $img ?>" class="profile-img" id="preview">

        <form method="POST" enctype="multipart/form-data">

            <!-- Upload Image -->
            <div class="mb-3">
                <label class="form-label">Profile Image</label>
                <input type="file" name="image" class="form-control" onchange="previewImage(event)">
            </div>

            <!-- Name -->
            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="<?= $user['Name'] ?>" required>
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?= $user['Email'] ?>" required>
            </div>

            <!-- Phone -->
            <div class="mb-3">
                <label>Phone</label>
                <input type="text" name="phone" class="form-control" value="<?= $user['PhoneNo'] ?>">
            </div>

            <!-- Address -->
            <div class="mb-3">
                <label>Address</label>
                <textarea name="address" class="form-control"><?= $user['Address'] ?></textarea>
            </div>

            <!-- City -->
            <div class="mb-3">
                <label>City</label>
                <input type="text" name="city" class="form-control" value="<?= $user['City'] ?>">
            </div>

            <!-- DOB -->
            <div class="mb-3">
                <label>Date of Birth</label>
                <input type="date" name="dob" class="form-control" value="<?= $user['DOB'] ?>">
            </div>

            <!-- BUTTONS -->
            <div class="text-center">
                <button type="submit" name="update" class="btn btn-primary">
                    Update Profile
                </button>

                <a href="UserProfile.php" class="btn btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
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
    function previewImage(event){
        const reader = new FileReader();
        reader.onload = function(){
            document.getElementById('preview').src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }
    </script>

</body>
</html>