<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Home | BookSpark Library</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background-color: #ffffff;
        }

        /* ===== Top Panel ===== */
        .header {
           display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 30px 40px;
            background-color: #2c3e50;
            color: white;
        }

        .logo-section {
             display: flex;
            align-items: center;
            gap: 35px;
        }

        .logo {
            width: 45px;
            height: 45px;
            background-color: #f1c40f;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
            color: #2c3e50;
        }

        .logo img{
            width: 90px;
            height: auto;
        }


        .site-name {
            font-size: 40px;
            font-weight: bold;
        }

        /* ===== Main Content ===== */
        .main-content {
            height: calc(100vh - 70px);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .main-content h1 {
            font-size: 50px;
            color: #2c3e50;
            margin-bottom: 25px;
        }

        .button-group {
            display: flex;
            gap: 25px;
        }

        .button-group button {
            padding: 12px 28px;
            font-size: 25px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            color: white;
        }

        .login-btn {
            background-color: #1abc9c;
        }

        .register-btn {
            background-color: #3498db;
        }

        .button-group button:hover {
            opacity: 0.9;
        }
    </style>
</head>

<body>

    <!-- Top Panel -->
    <div class="header">
        <div class="logo-section">
            <div class="logo">
                <img src="logo.jpeg" alt="BookSpark Logo">
            </div>
            <div class="site-name"><?php echo (!empty($_SESSION['site_name'])) ? $_SESSION['site_name'] : 'Book Spark'; ?></div>
        </div>
    </div>

    <!-- Middle Content -->
    <div class="main-content">
        <h1>Welcome to User Portal</h1>

        <div class="button-group">
            <button class="login-btn" onclick="window.location.href='UserLogin.php'">
                Login
            </button>

            <button class="register-btn" onclick="window.location.href='UserRegister.php'">
                Registration
            </button>
        </div>
    </div>

</body>
</html>


