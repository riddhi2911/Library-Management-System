<?php
session_start();
include("Connection.php");

$user_id = $_SESSION['UserId'];
$name = $_SESSION['Name'];

// ✅ Get all unpaid fines
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

// Selected book
$selected = isset($_GET['issue_id']) ? $_GET['issue_id'] : null;
$data = null;

foreach($fines as $f){
    if($f['IssueId'] == $selected){
        $data = $f;
        break;
    }
}

// Payment
$msg = "";
if(isset($_POST['pay'])){
    $issue_id = $_POST['issue_id'];

    mysqli_query($conn,"
    UPDATE Fine SET Status='Paid' WHERE IssueId='$issue_id'
    ");

    $msg = "Fine Paid Successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Pay Fine</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body{margin:0;font-family:Arial;}
.header{display:flex;justify-content:space-between;background:#2c3e50;color:white;padding:30px;}
.container{display:flex;justify-content:center;align-items:center;height:90vh;}
.box{background:white;padding:30px;width:420px;border-radius:12px;box-shadow:0 5px 20px rgba(0,0,0,0.2);}
.field{margin:10px 0;}
.field input,select{width:100%;padding:10px;}
.btn{width:100%;padding:12px;background:#27ae60;color:white;border:none;}
.fine{text-align:center;color:red;font-size:20px;margin:15px;}
.msg{text-align:center;margin-top:10px;color:green;}
</style>
</head>

<body>

<div class="header">
<h2><?php echo (!empty($_SESSION['site_name'])) ? $_SESSION['site_name'] : 'Book Spark'; ?> - Pay Fine</h2>
</div>

<div class="container">
<div class="box">

<h3 align="center">Fine Details</h3>

<?php if(empty($fines)){ ?>

    <p align="center">No unpaid fines!</p>

<?php } else { ?>

<!-- Select Book -->
<form method="GET">
<div class="field">
<label>Select Book</label>
<select name="issue_id" onchange="this.form.submit()">
<option value="">-- Select Book --</option>

<?php foreach($fines as $f){ ?>
<option value="<?= $f['IssueId'] ?>" 
<?= ($selected == $f['IssueId']) ? 'selected' : '' ?>>
<?= $f['Title'] ?>
</option>
<?php } ?>

</select>
</div>
</form>

<?php if($data){ ?>

<form method="POST">

<input type="hidden" name="issue_id" value="<?= $data['IssueId'] ?>">

<!-- <div class="field">
<label>Name</label>
<input value="<?= $name ?>" readonly>
</div> -->

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

<button class="btn" name="pay">Pay Now</button>

</form>

<?php } ?>

<?php } ?>

<?php if($msg){ ?>
<div class="msg"><?= $msg ?></div>
<?php } ?>

</div>
</div>

</body>
</html>