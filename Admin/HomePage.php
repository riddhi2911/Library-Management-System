<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BookSpark Library Management System</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background-color: #faf3e0;
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

        .switch-btn {
            padding: 20px 18px;
            background-color: #1abc9c;
            border: none;
            border-radius: 6px;
            font-size: 20px;
            cursor: pointer;
            color: white;
            font-weight: bold;
        }

        /* ===== Main Content ===== */
        .main-content {
            height: calc(100vh - 80px);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 15px;
        }

        .main-content h1 {
            font-size: 45px;
            color: #2c3e50;
            margin-bottom: 15px;
        }

        .main-content p {
            max-width: 700px;
            font-size: 20px;
            color: #555;
            line-height: 1.6;
        }

        .role-text {
            margin-top: 15px;
            font-size: 18px;
            font-weight: bold;
            color: #1abc9c;
        }
    </style>

    <!-- Switch Logic -->
    <script>
        let role = "User";

        function switchRole() {
            if (role === "User") {
                role = "Admin";
                window.location.href = "AdminLogin.php";
                document.getElementById("roleText").innerText = "Current Mode: Admin";
                document.getElementById("switchBtn").innerText = "Switch to User";
            } else {
                role = "User";
                window.location.href = "userHomepage.php";
                document.getElementById("roleText").innerText = "Current Mode: User";
                document.getElementById("switchBtn").innerText = "Switch to Admin";
            }
        }
    </script>
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

        <button class="switch-btn" id="switchBtn" onclick="switchRole()">
            Switch to Admin/
        </button>
    </div>

    <!-- Middle Content -->
    <div class="main-content">
        <h1>Library Management System</h1>
        <p>
            BookSpark Library Management System is designed to manage books,
            users, issue and return operations efficiently. It helps librarians
            and students maintain accurate records, save time, and reduce
            manual work through a simple and user-friendly interface.
        </p>

        <div class="role-text" id="roleText">
            Current Mode: User
        </div>
    </div>

</body>
</html>