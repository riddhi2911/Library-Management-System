<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Admin Login | BookSpark Library</title>

    <style>
        /*===== Full Page ======*/
        body {
        margin: 0;
        font-family: 'Segoe UI', sans-serif;
        background: url('library.jpg') no-repeat center center/cover;
        height: 100vh;
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

        /* Center Container */
        .login-container {
            position: relative;
            z-index: 2;
            height: calc(100vh - 80px);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-box {
            background: rgba(255,255,255,0.95);
            padding: 40px;
            width: 350px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            text-align: center;
        }

        .login-box h2 {
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .input-group{
            display: flex;
            align-items: center;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 8px;
            margin: 10px 0;
        }

        .input-group i {
            color: #2c3e50;
            margin-right: 8px;
            font-size: 16px;
            cursor: pointer;
        }

        .input-group input{
            border: none;
            outline: none;
            width: 100%;
            font-size: 14px;
        }

        .login-box button {
            width: 100%;
            padding: 10px;
            margin-top: 15px;
            background-color: #1abc9c;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        .login-box button:hover {
            background-color: #16a085;
        }
    </style>
</head>

<body>

    <?php
    session_start();

    $usernameErr = $passwordErr = $loginErr = "";

    if(isset($_POST['login']))
    {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        // Validation
        if(empty($username))
        {
            $usernameErr = "Username is required!";
        }

        if(empty($password))
        {
            $passwordErr = "Password is required!";
        }

        // Static Login
        if(empty($usernameErr) && empty($passwordErr))
        {
            if($username === "Admin" && $password === "Admin@123")
            {
                $_SESSION['admin'] = $username;
                header("Location: AdminIndex.php");
                exit();
            }
            else
            {
                $loginErr = "Invalid Username or Password!";
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


    <div class="background">
        <div class="overlay"></div> 
        <!-- Login Form -->
        <div class="login-container">
            <div class="login-box">
                <h2>Admin Login</h2>

                <form method="POST">

                    <div class="input-group">
                        <i class="fa fa-user"></i>
                        <input type="text" name="username" placeholder="Username">
                    </div>
                    <span style="color:red;"><?php echo $usernameErr; ?></span>

                    <div class="input-group">
                        <i class="fa fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Password">
                        <i class="fa fa-eye" onclick="togglePassword()" id="eyeIcon"></i>
                    </div>
                    <span style="color:red;"><?php echo $passwordErr; ?></span>

                    <button type="submit" name="login">Login</button>
                    <p style="color:red;"><?php echo $loginErr; ?></p>

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