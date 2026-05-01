<?php
session_start();
include('Connection.php');

$user_id = $_SESSION['UserId'];

// JOIN with Book table to get Title
$query = "
SELECT IssueBook.IssueDate, Book.Title
FROM IssueBook
JOIN Book ON IssueBook.BookId = Book.BookId
WHERE IssueBook.UserId='$user_id'
";

$result = mysqli_query($conn, $query);

if(!$result){
    die("Query Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Issued Books</title>

<style>
    body{
        margin:0;
        font-family: 'Segoe UI', sans-serif;
        background:#f4f6f9;
    }

    .container{
        width:80%;
        margin:40px auto;
        background:#fff;
        padding:25px;
        border-radius:10px;
        box-shadow:0 0 10px rgba(0,0,0,0.1);
    }

    h2{
        text-align:center;
        color:#2c3e50;
        margin-bottom:20px;
    }

    table{
        width:100%;
        border-collapse:collapse;
        border-radius:10px;
        overflow:hidden;
    }

    th{
        background:#2c3e50;
        color:white;
        padding:12px;
        text-align:center;
    }

    td{
        padding:10px;
        text-align:center;
        border-bottom:1px solid #ddd;
    }

    tr:nth-child(even){
        background:#f2f2f2;
    }

    tr:hover{
        background:#dfe6e9;
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

        <h2>Issued Books</h2>

        <table>
            <tr>
                <th>No.</th>
                <th>Book Name</th>
                <th>Issue Date</th>
            </tr>

            <?php 
            $no = 1; // Serial number

            if(mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_assoc($result)){ 
            ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $row['Title']; ?></td>
                <td><?= $row['IssueDate']; ?></td>
            </tr>
            <?php 
                }
            }else{
                echo "<tr><td colspan='3' class='no-data'>No issued books found</td></tr>";
            }
            ?>

        </table>

    </div>

</body>
</html>