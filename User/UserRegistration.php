<?php
include("Connection.php");

$nameErr = $emailErr = $passwordErr = $mobileErr = $AddressErr = $CityErr = $DOBErr = $ImageErr = $successMsg = "";

if(isset($_POST['register']))
{
    $name = trim($_POST['Name']);
    $email = trim($_POST['Email']);
    $password = trim($_POST['Password']);
    $mobile = trim($_POST['PhoneNo']);
    $address = trim($_POST['Address']);
    $city = trim($_POST['City']);
    $dob = trim($_POST['DOB']);

    // Profile Image
    $ProfileImage = $_FILES['profile_image']['name'];
    $tmpName = $_FILES['profile_image']['tmp_name'];
    $folder = "uploads/" . time() . "_" . $ProfileImage;

    // Name validation
    if(empty($name)) {
        $nameErr = "Name is required!";
    } elseif(!preg_match("/^[A-Za-z ]+$/", $name)) {
        $nameErr = "Only letters allowed";
    }

    // Email validation
    if(empty($email)) {
        $emailErr = "Email is required!";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format!";
    }

    // Password validation
    if(empty($password)) {
        $passwordErr = "Password is required!";
    } elseif(!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/", $password)) {
        $passwordErr = "Password not strong enough!";
    }

    // Mobile validation
    if(empty($mobile)) {
        $mobileErr = "Mobile number is required!";
    } elseif(!preg_match("/^[6-9][0-9]{9}$/", $mobile)) {
        $mobileErr = "Invalid mobile number!";
    }

    //Address Validation
    if(empty($address)){
        $AddressErr = "Address is required!";
    } 

    //city validation
    if(empty($city)){
        $CityErr = "Please Select the city!";
    }

    //birthdate validation
    if(empty($dob)){
        $DOBErr = "Please Select the Date of Birth!";
    }

    // Image validation
    if(empty($ProfileImage)){
        $ImageErr = "Profile picture is required!";
    } else {
        $fileType = strtolower(pathinfo($ProfileImage, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg','jpeg','png','gif'];

        if(!in_array($fileType, $allowedTypes)){
            $ImageErr = "Only JPG, JPEG, PNG, GIF allowed!";
        }
    }

    // Insert into database
    if(empty($nameErr) && empty($emailErr) && empty($passwordErr) && empty($mobileErr) && empty($AddressErr) && empty($CityErr) && empty($DOBErr) && empty($ImageErr))
    {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Upload image
        if(move_uploaded_file($tmpName, $folder)){

    $query = "INSERT INTO Users (Name, Email, Password, PhoneNo, Address, City, DOB, profile_image)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssssssss", $name, $email, $hashed_password, $mobile, $address, $city, $dob, $folder);

    if(mysqli_stmt_execute($stmt)) {
        header("Location:UserLogin.php");
        exit();
    } else {
        $successMsg = "Database error!";
    }

} else {
    $ImageErr = "Image upload failed!";
}
        
if(isset($stmt)){
    mysqli_stmt_close($stmt);
}            
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->


    <title>User Registration | BookSpark Library</title>

    <style>
        /*=====Full Page =====*/
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: url('library.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        /* Dark overlay */
        body::before {
            content: "";
            position: fixed;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            top: 0;
            left: 0;
            z-index: 0;
        }

        /* Header */
        .header {
            position: relative;
            z-index: 2;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 40px;
            background-color: rgba(44, 62, 80, 0.9);
            color: white;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .logo img {
            width: 70px;
        }

        .site-name {
            font-size: 28px;
            font-weight: bold;
        }

        /* ===== Background Image ===== */
        .background {
            background: url('https://images.unsplash.com/photo-1524995997946-a1c2e315a42f') no-repeat center center/cover;
            height: 100vh;
            width: 100%;
            position: relative;
        }

        /* Overlay for dark effect */
        .overlay {
            background-color: rgba(0, 0, 0, 0.6);
            height: 100%;
            width: 100%;
            position: absolute;
            top: 0;
            left: 0;
        }

        /* Center container */
        .register-container {
            position: relative;
            z-index: 2;
            height: calc(100vh - 80px);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Card */
        .register-box {
            background: rgba(255,255,255,0.95);
            padding: 20px;
            width: 420px;
            border-radius: 15px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.5);
        }

        /* Title */
        .register-box h2 {
            margin-bottom: 20px;
            color: #2c3e50;
        }

        /* Input group */
        .input-group {
            display: flex;
            align-items: center;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 10px;
            margin: 12px 0;
            background: #fff;
        }

        .input-group i {
            color: #555;
            margin-right: 10px;
        }

        /* Inputs */
        .input-group input,
        .input-group textarea,
        .input-group select {
            border: none;
            outline: none;
            width: 100%;
            font-size: 14px;
            background: transparent;
        }

        /* Button */
        .register-box button {
            width: 100%;
            padding: 12px;
            margin-top: 15px;
            background: #e74c3c;
            border: none;
            border-radius: 6px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        .register-box button:hover {
            background: #c0392b;
        }

        /* File input */
        input[type="file"] {
            margin-top: 10px;
        }

        /* Error messages */
        span {
            font-size: 13px;
        }

        /* Success message */
        .success {
            color: green;
            margin-top: 10px;
        }

        /* .footer {
            background-color: #0d3b4c;
            padding: 10px;
            text-align: center;
        } */
    </style>
</head>

<body>

    <!-- Header -->
    <div class="header">
        <div class="logo-section">
            <div class="logo">
                <img src="logo.jpeg" alt="BookSpark Logo">
            </div>
            <div class="site-name">BookSpark</div>
        </div>
    </div>

<div class="background">

        <div class="overlay"></div> 
    <!-- Register Form -->
    <div class="register-container">
        <div class="register-box">
            <h2>User Registration</h2>

            <form method="POST" enctype="multipart/form-data">

                <div class="input-group">
                    <i class="fa fa-user"></i>
                    <input type="text" name="Name" placeholder="Full Name">
                </div>
                <span style="color:red;"><?php echo $nameErr; ?></span>

                <div class="input-group">
                    <i class="fa fa-envelope"></i>
                    <input type="email" name="Email" placeholder="Email Address">
                </div>
                <span style="color:red;"><?php echo $emailErr; ?></span>

                <div class="input-group">
                    <i class="fa fa-lock"></i>
                    <input type="password" id="password"name="Password" placeholder="Password">
                    <i class="fa fa-eye" onclick="togglePassword()" id="eyeIcon"></i>
                </div>
                <span style="color:red;"><?php echo $passwordErr; ?></span>

                <div class="input-group">
                    <i class="fa fa-phone"></i>
                    <input type="text" name="PhoneNo" placeholder="Mobile Number">
                </div>
                <span style="color:red;"><?php echo $mobileErr; ?></span>

                <div class="input-group">
                    <i class="fa fa-house"></i>
                    <textarea name="Address" rows="2" placeholder="Address"></textarea>
                </div>
                <span style="color:red;"><?php echo $AddressErr; ?></span>

                <div class="input-group">
                    <i class="fa fa-city"></i>
                    <select name="City">
                        <option value="">Select City</option>
                        <option value="surat">Surat</option>
                        <option value="mahesana">Mahesana</option>
                        <option value="ahemdabad">Ahmedabad</option>
                        <option value="vadodara">Vadodara</option>
                    </select>
                </div>
                <span style="color:red;"><?php echo $CityErr; ?></span>

                <div class="input-group">
                    <i class="fa fa-calendar-days"></i>
                    <input type="date" name="DOB">
                </div>
                <span style="color:red;"><?php echo $DOBErr; ?></span>

                <input type="file" name="profile_image">
                <span style="color:red;"><?php echo $ImageErr; ?></span>

                <button type="submit" name="register">Register</button>

                <div style="margin-top:10px;">
                    <a href="UserLogin.php" style="color:#3498db; text-decoration:none;">
                        Do you Have an Account? Login Here
                    </a>
                </div>

                <p class="success"><?php echo $successMsg; ?></p>
            </form>
        </div>
    </div>
</div>

    <!-- Show/Hide Password -->
    <script>
    function togglePassword() {
        var password = document.getElementById("password");
        var icon = document.getElementById("eyeIcon");

        if (password.type === "password") {
            password.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            password.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }
    </script>
    </body>
</html>


