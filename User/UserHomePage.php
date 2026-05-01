<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Home | BookSpark Library</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

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

        /* ===== Background Image ===== */
        .background {
            background: url('https://images.unsplash.com/photo-1524995997946-a1c2e315a42f') no-repeat center center;
            background-size: cover;
            min-height: 100vh;
            width: 100%;
            position: relative;
        }

        /* Overlay for dark effect */
        .overlay {
            background-color: rgba(0, 0, 0, 0.6);
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;   /* 🔥 ADD THIS */
        }

        /* ===== Main Content ===== */
        .main-content {
            position: relative;   /* 🔥 ADD THIS */
            z-index: 2;
            height: calc(100vh - 70px);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .main-content h1 {
            font-size: 50px;
            color: #ffffff;
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

        .footer {
            background-color: #0d3b4c;
            padding: 10px;
            text-align: center;
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
            <div class="site-name">BookSpark</div>
        </div>
    </div>

    <div class="background">

        <div class="overlay"></div> 
            <!-- Middle Content -->
            <div class="main-content">
                <h1>Welcome to User Portal</h1>

                <div class="button-group">
                    <button class="login-btn" onclick="window.location.href='UserLogin.php'">
                        Login
                    </button>

                    <button class="register-btn" onclick="window.location.href='UserRegistration.php'">
                        Registration
                    </button>
                </div>
            </div>
    </div>
        <footer class="footer mt-5">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="text-white">About</h5>
                            <p class="text-white-50">An advanced digital library platform that simplifies book management, improves accessibility, and enhances user experience.</p>
                        </div>
                        <!-- <div class="col-md-4">
                            <h5 class="text-white">Quick Links</h5>
                            <ul class="list-unstyled">
                                <li><a href="AdminIndex.php" class="text-white-50 text-decoration-none">Dashboard</a></li>
                                <li><a href="AdminManageBook.php" class="text-white-50 text-decoration-none">Books</a></li>
                                <li><a href="AdminManageUser.php" class="text-white-50 text-decoration-none">Users</a></li>
                            </ul>
                        </div> -->
                        <div class="col-md-6">
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


