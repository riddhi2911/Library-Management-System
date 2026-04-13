<?php
include("Connection.php");
session_start();

/*FETCH FINE DATA (ADDED UserId)*/
$query = "
SELECT 
    Fine.FineId,
    Fine.DaysLate,
    Fine.FineAmount,
    Fine.Status,
    Users.UserId,
    Users.Name,
    Book.Title
FROM Fine
JOIN IssueBook ON Fine.IssueId = IssueBook.IssueId
JOIN Users ON IssueBook.UserId = Users.UserId
JOIN Book ON IssueBook.BookId = Book.BookId
ORDER BY Fine.FineId DESC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Fine</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body { margin: 0; }

/* Sidebar */
.sidebar {
    width: 250px;
    min-height: 100vh;
    background: #0d3b4c;
    color: white;
    position: fixed;
}

.sidebar a {
    color: white;
    display: block;
    padding: 12px;
    text-decoration: none;
}

.sidebar a:hover { background: #145a6f; }

.sidebar a.active {
    background: #145a6f;
    color: white;
    font-weight: bold;
    border-left: 4px solid #fff;
}

.logo { text-align: center; padding: 20px; }

.logo img { width: 80px; }

/* Main */
.main { margin-left: 250px; padding: 20px; }

/* Header */
.header {
    background: #145a6f;
    color: white;
    padding: 15px;
    border-radius: 5px;
}

.custom-header th {
    background: #0d6efd;
    color: white;
}

.footer {
    background-color: #0d3b4c;
    padding: 10px;
    text-align: center;
}

</style>
</head>

<body>

<!-- Sidebar -->
<div class="sidebar">

    <div class="logo">
        <a href="index.php">
            <img src="logo.jpeg" alt="Logo">
        </a>
        <h5><?php echo (!empty($_SESSION['site_name'])) ? $_SESSION['site_name'] : 'Book Spark'; ?></h5>
    </div>

    <a href="AdminIndex.php"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
    <a href="AdminManageBook.php"><i class="fas fa-book me-2"></i> Manage Book</a>
    <a href="AdminManageUser.php"><i class="fas fa-users me-2"></i> Manage User</a>
    <a href="AdminRequestBook.php"><i class="fas fa-book-open me-2"></i> Requested Book</a>
    <a href="AdminIssueBook.php"><i class="fas fa-arrow-up me-2"></i> Issued Books</a>
    <a href="AdminReturnBook.php"><i class="fas fa-arrow-down me-2"></i> Return Book</a>
    <a href="AdminFine.php" class="<?php if(basename($_SERVER['PHP_SELF'])=='AdminFine.php') echo 'active'; ?>"><i class="fas fa-money-bill-wave me-2"></i> Fine</a>
    <a href="AdminSettings.php"><i class="fas fa-cog me-2"></i> Settings</a>
    <a href="AdminLogout.php" ><i class="fas fa-sign-out-alt me-2"></i> Logout</a>

</div>

<!-- Main Content -->
<div class="main">

    <div class="header mb-4">
        <h4>Librarian Fine Panel</h4>
    </div>

    <div class="container mt-5" style="margin-bottom: 100px;">

        <div class="card p-4 shadow">
            <h4 class="text-center mb-4">Fine Details</h4>

            <table class="table table-bordered text-center">

                <thead class="custom-header">
                    <tr>
                        <th>No</th>
                        <th>User</th>
                        <th>Book</th>
                        <th>Days Late</th>
                        <th>Fine Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                <?php
                if(mysqli_num_rows($result) > 0){

                    $i = 1;

                    while($row = mysqli_fetch_assoc($result)){

                        $userId = $row['UserId'];

                        /* COUNT UNPAID FINES */
                        $countQuery = mysqli_query($conn, "
                            SELECT COUNT(*) AS total 
                            FROM Fine
                            JOIN IssueBook ON Fine.IssueId = IssueBook.IssueId
                            WHERE IssueBook.UserId = $userId 
                            AND Fine.Status = 'Unpaid'
                        ");

                        $countData = mysqli_fetch_assoc($countQuery);
                        $unpaidCount = $countData['total'];

                        /* BLOCK USER IF 3 OR MORE UNPAID FINES */
                        if($unpaidCount >= 3){
                            mysqli_query($conn, "
                                UPDATE Users 
                                SET Status = 'Block' 
                                WHERE UserId = $userId 
                                AND Status != 'Block'
                            ");
                        }
                ?>

                    <tr style="<?php echo ($row['Status'] == 'Unpaid') ? 'background:#ffe5e5;' : ''; ?>">

                        <td><?php echo $i++; ?></td>
                        <td><?php echo $row['Name']; ?></td>
                        <td><?php echo $row['Title']; ?></td>
                        <td><?php echo $row['DaysLate']; ?></td>

                        <td>
                            <span style="color:red;">₹<?php echo $row['FineAmount']; ?></span>
                        </td>

                        <td>
                            <?php if($row['Status'] == 'Paid'){ ?>
                                <span class="badge bg-success">Paid</span>
                            <?php } else { ?>
                                <span class="badge bg-danger">Unpaid</span>
                            <?php } ?>
                        </td>

                    </tr>

                <?php
                    }
                } else {
                    echo "<tr><td colspan='6'>No Fine Records Found</td></tr>";
                }
                ?>

                </tbody>
            </table>

        </div>

    </div>

    <!-- Footer -->
    <footer class="footer mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5 class="text-white">About</h5>
                    <p class="text-white-50">An advanced digital library platform that simplifies book management, improves accessibility, and enhances user experience.</p>
                </div>
                <div class="col-md-4">
                    <h5 class="text-white">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="AdminIndex.php" class="text-white-50 text-decoration-none">Dashboard</a></li>
                        <li><a href="AdminManageBook.php" class="text-white-50 text-decoration-none">Manage Books</a></li>
                        <li><a href="AdminManageUser.php" class="text-white-50 text-decoration-none">Manage Users</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
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

</div>

</body>
</html>