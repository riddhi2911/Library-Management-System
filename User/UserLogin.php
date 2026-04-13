<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>User Login | BookSpark Library</title>

    <style>
        /*===== Full Page ======*/
        body {
        margin: 0;
        font-family: 'Segoe UI', sans-serif;
        background: url('library.jpg') no-repeat center center/cover;
        height: 100vh;
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

        /* Center Container */
        .login-container {
            position: relative;
            z-index: 2;
            height: calc(100vh - 80px);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Card */
        .login-box {
            background: rgba(255,255,255,0.95);
            padding: 30px 35px;
            width: 380px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.4);
            text-align: center;
        }

        /* Title */
        .login-box h2 {
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
        .input-group input {
            border: none;
            outline: none;
            width: 100%;
            font-size: 14px;
            background: transparent;
        }

        /* Captcha image */
        .login-box img {
            margin-top: 10px;
            border-radius: 5px;
        }

        /* Button */
        .login-box button {
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

        .login-box button:hover {
            background: #c0392b;
        }

        /* Error text */
        span {
            font-size: 13px;
        }

        /* Login error */
        .error-msg {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>

<body>

<?php
include("Connection.php");
session_start();

$email = $password = $captcha = "";
$emailErr = $passwordErr = $captchaErr = $loginErr = "";

if(isset($_POST['login']))
{
    $email = trim($_POST['Email']);
    $password = trim($_POST['Password']);
    $captcha = trim($_POST['Captcha']);

    //Email validation
    if(empty($email)){
        $emailErr = "Email is required!";
    }

    //Password validation
    if(empty($password)){
        $passwordErr = "Password is required!";
    }

    //Captcha validation
    if(empty($captcha)){
        $captchaErr = "Captcha is required!";
    }
    elseif($captcha != $_SESSION['captcha']){
        $captchaErr = "Captcha does not match!";
    }

    //If no errors → proceed
    if(empty($emailErr) && empty($passwordErr) && empty($captchaErr))
    {
        //Use prepared statement to prevent SQL injection
        $query = "SELECT * FROM Users WHERE Email = ?";
        $stmt = mysqli_prepare($conn, $query);
        
        if(!$stmt) {
            $loginErr = "Database error!";
        } else {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if($result && mysqli_num_rows($result) == 1)
            {
                $row = mysqli_fetch_assoc($result);

                if(password_verify($password, $row['Password']))
                {
                    $_SESSION['UserId'] = $row['UserId'];
                    $_SESSION['Name'] = $row['Name'];

                    header("Location: UserDashboard.php");
                    exit();
                }
                else
                {
                    $loginErr = "Invalid Password!";
                }
            }
            else
            {
                $loginErr = "User not found!";
            }
            
            mysqli_stmt_close($stmt);
        }
    }
}
?>

    <!-- Top Panel -->
    <div class="header">
        <div class="logo-section">
            <div class="logo">
                <img src="logo.jpeg" alt="BookSpark Logo">
            </div>
            <div class="site-name">BookSpark</div>
        </div>
    </div>

    <!-- Login Form -->
    <div class="login-container">
    <div class="login-box">
        <h2>User Login</h2>

        <form method="POST">

            <!-- Email -->
            <div class="input-group">
                <i class="fa fa-envelope"></i>
                <input type="email" name="Email" placeholder="Email"
                value="<?php echo htmlspecialchars($email); ?>">
            </div>
            <span style="color:red;"><?php echo $emailErr; ?></span>

            <!-- Password -->
            <div class="input-group">
                <i class="fa fa-lock"></i>
                <input type="password" id="password" name="Password" placeholder="Password">
                <i class="fa fa-eye" onclick="togglePassword()" id="eyeIcon"></i>
            </div>
            <span style="color:red;"><?php echo $passwordErr; ?></span>

            <!-- Captcha -->
            <img src="Captcha.php" alt="captcha">
            <div class="input-group">
                <i class="fa fa-shield"></i>
                <input type="text" name="Captcha" placeholder="Enter Captcha">
            </div>
            <span style="color:red;"><?php echo $captchaErr; ?></span>

            <button type="submit" name="login">Login</button>

            <div style="margin-top:10px;">
                <a href="ForgotPassword.php" style="color:#3498db; text-decoration:none;">
                    Forgot Password?
                </a>
            </div>
            <p class="error-msg"><?php echo $loginErr; ?></p>

        </form>
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
