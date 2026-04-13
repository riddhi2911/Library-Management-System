<?php
session_start();
include('Connection.php');

if (!isset($_SESSION['UserId'])) {
    header("Location: UserLogin.php");
    exit();
}

$userId = $_SESSION['UserId'];

$query = "SELECT * FROM Users WHERE UserId='$userId'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<title>Edit Profile</title>

    <style>
        body{
            font-family: Arial;
            background:#faf3e0;
        }

        .card{
            width:420px;
            margin:80px auto;
            background:#fff;
            padding:30px;
            border-radius:15px;
            box-shadow:0 10px 25px rgba(0,0,0,0.12);
        }

        input, textarea{
            width:100%;
            padding:10px;
            margin:10px 0;
            border:1px solid #ccc;
            border-radius:8px;
        }

        .input-group{
            display: flex;
            align-items: center;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 1px;
            margin: 10px 0;
        }

        .input-group i {
            color: #2c3e50;
            margin-right: 8px;
            font-size: 16px;
        }

        .input-group input{
            border: none;
            outline: none;
            width: 100%;
            font-size: 14px;
        }

        button{
            width:100%;
            padding:12px;
            background:#1abc9c;
            border:none;
            color:white;
            font-size:16px;
            border-radius:25px;
            cursor:pointer;
        }
        
        button:hover{
            background:#16a085;
        }
    </style>
</head>

<body>

<div class="card">
    <h2>Edit Profile</h2>

    <form method="POST" action="updateProfile.php" enctype="multipart/form-data">

        <div class="input-group">
            <i class="fa fa-user"></i>
            <input type="text" name="Name" value="<?= $user['Name']; ?>" placeholder="Full Name">
        </div>
            <!--<span style="color:red;"><?php echo $nameErr; ?></span>-->

        <div class="input-group">
            <i class="fa fa-envelope"></i>
            <input type="email" name="Email" value="<?= $user['Email']; ?>" placeholder="Email">
        </div>
            <!-- <span style="color:red;"><?php echo $emailErr; ?></span> -->

        <div class="input-group">
            <i class="fa fa-lock"></i>
            <input type="password" name="Password" value="<?= $user['Password']; ?>" placeholder="Password">        
        </div>
            <!-- <span style="color:red;"><?php echo $passwordErr; ?></span> -->

        <div class="input-group">
            <i class="fa fa-phone"></i>
            <input type="text" name="PhoneNo" value="<?= $user['PhoneNo']; ?>" placeholder="Mobile Number">        \
        </div>
            <!-- <span style="color:red;"><?php echo $mobileErr; ?></span> -->

        <div class="input-group">
            <i class="fa fa-house"></i>
            <textarea name="Address"><?= $user['Address']; ?></textarea>       
        </div>
            <!-- <span style="color:red;"><?php echo $AddressErr; ?></span> -->

        <div class="input-group">
            <i class="fa fa-city"></i>
            <label> Select City: </label>
            <select name="City">
                <option value="surat" <?= ($user['City']=='surat')?'selected':''; ?>>Surat</option>
                <option value="mahesana" <?= ($user['City']=='mahesana')?'selected':''; ?>>Mahesana</option>
                <option value="ahemdabad" <?= ($user['City']=='ahemdabad')?'selected':''; ?>>Ahemdabad</option>
                <option value="vadodara" <?= ($user['City']=='vadodara')?'selected':''; ?>>Vadodara</option>
            </select>
        </div>
            <!-- <span style="color:red;"><?php echo $CityErr; ?></span> -->

        <div class="input-group">
            <i class="fa fa-calendar-days"></i>
            <input type="date" name="DOB" value="<?= $user['DOB']; ?>">        
        </div>
            <!-- <span style="color:red;"><?php echo $DOBErr; ?></span>    -->

            <!-- Profile Image -->
            <input type="file" name="profile_image">
            <!-- <span style="color:red;"><?php echo $ImageErr; ?></span> -->

            <button type="submit" id="updateBtn" name="update">Update</button>
            <!-- <p style="color:green;"><?php echo $successMsg; ?></p>
            <p id="error"></p> -->
    </form>
</div>

</body>
</html>
