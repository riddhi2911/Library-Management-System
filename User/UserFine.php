<?php
session_start();
include("Connection.php");

$user_id = $_SESSION['UserId'];
$name = $_SESSION['Name'];

/* ===== GET UNPAID FINES ===== */
$query = "
SELECT Fine.*, IssueBook.IssueDate, IssueBook.DueDate,
       Book.Title, ReturnBook.ReturnDate
FROM Fine
JOIN IssueBook ON Fine.IssueId = IssueBook.IssueId
JOIN Book ON IssueBook.BookId = Book.BookId
LEFT JOIN ReturnBook ON IssueBook.IssueId = ReturnBook.IssueId
WHERE IssueBook.UserId='$user_id' AND Fine.Status='Unpaid'
";

$result = mysqli_query($conn, $query);

$fines = [];
while($row = mysqli_fetch_assoc($result)){
    $fines[] = $row;
}

/* ===== SELECT BOOK ===== */
$selected = isset($_GET['issue_id']) ? $_GET['issue_id'] : null;
$data = null;

foreach($fines as $f){
    if($f['IssueId'] == $selected){
        $data = $f;
        break;
    }
}

/* ===== PAYMENT ===== */
$msg = "";
if(isset($_POST['pay'])){
    $issue_id = $_POST['issue_id'];

    mysqli_query($conn,"UPDATE Fine SET Status='Paid' WHERE IssueId='$issue_id'");
    $msg = "Fine Paid Successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Pay Fine</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

    /* ===== FULL PAGE ===== */
    html, body {
        height: 100%;
        margin: 0;
    }

    body {
        display: flex;
        flex-direction: column;
        font-family: Arial;
        background: #f4f6f9;
    }

    /* ===== HEADER ===== */
    .header {
        background: #2c3e50;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: white;
    }

    .logo {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 24px;
        font-weight: bold;
    }

    .logo img {
        width: 60px;
    }

    /* USER MENU */
    .user-menu {
        position: relative;
    }

    .dropdown-menu-custom {
        display: none;
        position: absolute;
        right: 0;
        top: 45px;
        background: white;
        min-width: 150px;
        border-radius: 8px;
        box-shadow: 0px 5px 10px rgba(0,0,0,0.2);
    }

    .dropdown-menu-custom a {
        display: block;
        padding: 10px;
        color: #333;
        text-decoration: none;
    }

    .dropdown-menu-custom a:hover {
        background: #1abc9c;
        color: white;
    }

    /* ===== MAIN CONTENT ===== */
    .main-content {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        padding: 40px 0;
    }

    /* CARD */
    .box {
        background: white;
        padding: 30px;
        width: 420px;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.2);
    }

    /* FORM */
    .field {
        margin: 10px 0;
    }

    .field input, select {
        width: 100%;
        padding: 10px;
    }

    /* BUTTON */
    .btn-pay {
        width: 100%;
        padding: 12px;
        background: #27ae60;
        color: white;
        border: none;
        border-radius: 5px;
    }

    /* TEXT */
    .fine {
        text-align: center;
        color: red;
        font-size: 20px;
        margin: 15px;
    }

    .msg {
        text-align: center;
        margin-top: 10px;
        color: green;
    }

    /* ===== FOOTER ===== */
    .footer {
        background-color: #0d3b4c;
        padding: 20px 0;
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

        <div class="user-menu">
            <i class="fa fa-user-circle fa-2x" style="cursor:pointer;" onclick="toggleMenu()"></i>

            <div id="userDropdown" class="dropdown-menu-custom">
                <a href="UserProfile.php">My Profile</a>
                <a href="UserLogout.php">Logout</a>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <div class="box">

            <h3 class="text-center">Fine Details</h3>

            <?php if(empty($fines)){ ?>
                <p class="text-center">No unpaid fines!</p>

            <?php } else { ?>

            <form method="GET">
                <div class="field">
                    <label>Select Book</label>
                    <select name="issue_id" onchange="this.form.submit()">
                        <option value="">-- Select Book --</option>

                        <?php foreach($fines as $f){ ?>
                        <option value="<?= $f['IssueId'] ?>" <?= ($selected == $f['IssueId']) ? 'selected' : '' ?>>
                        <?= $f['Title'] ?>
                        </option>
                        <?php } ?>

                    </select>
                </div>
            </form>

            <?php if($data){ ?>

            <form method="POST">

                <input type="hidden" name="issue_id" value="<?= $data['IssueId'] ?>">

                <div class="field">
                    <label>Book</label>
                    <input value="<?= $data['Title'] ?>" readonly>
                </div>

                <div class="field">
                    <label>Issue Date</label>
                    <input value="<?= $data['IssueDate'] ?>" readonly>
                </div>

                <div class="field">
                    <label>Due Date</label>
                    <input value="<?= $data['DueDate'] ?>" readonly>
                </div>

                <div class="field">
                    <label>Return Date</label>
                    <input value="<?= $data['ReturnDate'] ?>" readonly>
                </div>

                <div class="fine">
                    Fine: ₹ <?= $data['FineAmount'] ?>
                </div>

                <button class="btn-pay" name="pay">Pay Now</button>

            </form>

            <?php } ?>

            <?php } ?>

            <?php if($msg){ ?>
            <div class="msg"><?= $msg ?></div>
            <?php } ?>

        </div>
    </div>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container">
            <div class="row text-center justify-content-center">

                <div class="col-md-6 mb-3">
                    <h5 class="text-white">About</h5>
                    <p class="text-white-50">
                        An advanced digital library platform that simplifies book management,
                        improves accessibility, and enhances user experience.
                    </p>
                </div>

                <div class="col-md-6 mb-3">
                    <h5 class="text-white">Contact</h5>
                    <p class="text-white-50 mb-1"><i class="fas fa-envelope me-2"></i> booksparkgmail.com</p>
                    <p class="text-white-50 mb-1"><i class="fas fa-phone me-2"></i> +91 98765 43210</p>
                    <p class="text-white-50 mb-1"><i class="fab fa-instagram me-2"></i> Book_Spark</p>
                    <p class="text-white-50"><i class="fas fa-clock me-2"></i> 9 AM - 6 PM</p>
                </div>

            </div>

            <hr style="border-color: rgba(255,255,255,0.2);">

            <div class="text-center text-white-50 py-2">
                © <?= date("Y") ?> BookSpark Library Management System. All rights reserved.
            </div>
        </div>
    </footer>

    <script>
    function toggleMenu() {
        let menu = document.getElementById("userDropdown");
        menu.style.display = (menu.style.display === "block") ? "none" : "block";
    }

    document.addEventListener("click", function(e){
        let menu = document.getElementById("userDropdown");
        if(!e.target.closest('.user-menu')){
            menu.style.display = "none";
        }
    });
    </script>

</body>
</html>