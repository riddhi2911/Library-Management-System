<?php
session_start();
include("Connection.php");

// ================= RETURN BOOK LOGIC =================
if(isset($_GET['id'])){

    $issueId = $_GET['id'];

    $query = "SELECT * FROM IssueBook WHERE IssueId = $issueId";
    $result_issue = mysqli_query($conn, $query);

    if($row = mysqli_fetch_assoc($result_issue)){

        $dueDate = $row['DueDate'];
        $returnDate = date("Y-m-d");

        $daysLate = 0;
        $fineAmount = 0;

        if(strtotime($returnDate) > strtotime($dueDate)){
            $daysLate = floor((strtotime($returnDate) - strtotime($dueDate)) / (60*60*24));
        }

        $fine_per_day = isset($_SESSION['fine_per_day']) ? $_SESSION['fine_per_day'] : 10;
        $fineAmount = $daysLate * $fine_per_day;

        // Update IssueBook
        mysqli_query($conn, "
        UPDATE IssueBook 
        SET Status='Returned' 
        WHERE IssueId = $issueId
        ");

        // Insert into ReturnBook
        mysqli_query($conn, "
        INSERT INTO ReturnBook (IssueId, ReturnDate, Status)
        VALUES ($issueId, '$returnDate', 'Returned')
        ");

        // Insert Fine
        $checkFine = mysqli_query($conn, "SELECT * FROM Fine WHERE IssueId = $issueId");

        if(mysqli_num_rows($checkFine) == 0 && $daysLate > 0){
            mysqli_query($conn, "
            INSERT INTO Fine (IssueId, DaysLate, FineAmount, Status)
            VALUES ($issueId, $daysLate, $fineAmount, 'Unpaid')
            ");
        }

        header("Location: ReturnBookHistory.php");
        exit();
    }
}

// ================= FETCH RETURN HISTORY =================

$user_id = $_SESSION['UserId'];

$query = "
SELECT 
    Book.Title,
    IssueBook.IssueDate,
    IssueBook.DueDate,
    ReturnBook.ReturnDate,
    ReturnBook.Status,
    IFNULL(Fine.FineAmount, 0) AS FineAmount
FROM ReturnBook
JOIN IssueBook ON ReturnBook.IssueId = IssueBook.IssueId
JOIN Book ON IssueBook.BookId = Book.BookId
LEFT JOIN Fine ON IssueBook.IssueId = Fine.IssueId
WHERE IssueBook.UserId = '$user_id'
ORDER BY ReturnBook.ReturnId DESC
";

$result = mysqli_query($conn, $query);

if(!$result){
    die("Query Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Return History</title>

<style>
body{
    margin:0;
    font-family: 'Segoe UI', sans-serif;
    background: #f4f6f9;
}

.container{
    width: 90%;
    margin: 40px auto;
    background: #fff;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

h2{
    text-align: center;
    color: #2c3e50;
    margin-bottom: 20px;
}

table{
    width: 100%;
    border-collapse: collapse;
    border-radius: 10px;
    overflow: hidden;
}

th{
    background: #2c3e50;
    color: white;
    padding: 12px;
    text-align: center;
}

td{
    padding: 10px;
    text-align: center;
    border-bottom: 1px solid #ddd;
}

tr:nth-child(even){
    background: #f2f2f2;
}

tr:hover{
    background: #dfe6e9;
}

.status-returned{
    color: green;
    font-weight: bold;
}

.amount{
    color: #e74c3c;
    font-weight: bold;
}

.no-data{
    text-align:center;
    padding:20px;
    color:#555;
}
</style>

</head>

<body>

<div class="container">

<h2> Return Book History</h2>

<table>
<tr>
    <th>No.</th>
    <th>Book</th>
    <th>Issue Date</th>
    <th>Due Date</th>
    <th>Return Date</th>
    <th>Fine Amount</th>
    <th>Status</th>
</tr>

<?php 
$no = 1;

if(mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_assoc($result)) { 
?>
<tr>
    <td><?= $no++; ?></td>
    <td><?= $row['Title'] ?></td>
    <td><?= $row['IssueDate'] ?></td>
    <td><?= $row['DueDate'] ?></td>
    <td><?= $row['ReturnDate'] ?></td>
    <td class="amount">₹<?= $row['FineAmount'] ?></td>
    <td class="status-returned"><?= $row['Status'] ?></td>
</tr>
<?php 
    }
}else{
    echo "<tr><td colspan='7' class='no-data'>No return records found</td></tr>";
}
?>

</table>

</div>

</body>
</html>