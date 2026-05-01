<?php
session_start();
include("Connection.php");

$user_id = $_SESSION['UserId'];

// ================= FETCH FINE HISTORY =================
$query = "
SELECT 
    Book.Title,
    IssueBook.IssueDate,
    IssueBook.DueDate,
    ReturnBook.ReturnDate,
    IFNULL(Fine.FineAmount, 0) AS FineAmount,
    IFNULL(Fine.Status, 'No Fine') AS FineStatus
FROM IssueBook
JOIN Book ON IssueBook.BookId = Book.BookId
LEFT JOIN ReturnBook ON IssueBook.IssueId = ReturnBook.IssueId
LEFT JOIN Fine ON IssueBook.IssueId = Fine.IssueId
WHERE IssueBook.UserId = '$user_id'
ORDER BY IssueBook.IssueId DESC
";

$result = mysqli_query($conn, $query);

if(!$result){
    die("Query Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Fine History</title>

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

    .amount{
        color: #e74c3c;
        font-weight: bold;
    }

    .paid{
        color: green;
        font-weight: bold;
    }

    .unpaid{
        color: red;
        font-weight: bold;
    }

    .no-fine{
        color: gray;
    }
</style>

</head>

<body>

    <div class="container">

        <h2> Fine History</h2>

        <table>
            <tr>
                <th>No.</th>
                <th>Book</th>
                <th>Issue Date</th>
                <th>Due Date</th>
                <th>Return Date</th>
                <th>Fine Amount</th>
                <th>Fine Status</th>
            </tr>

            <?php 
            $no = 1;

            if(mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_assoc($result)){
            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $row['Title']; ?></td>
                <td><?php echo $row['IssueDate']; ?></td>
                <td><?php echo $row['DueDate']; ?></td>
                <td><?php echo $row['ReturnDate'] ? $row['ReturnDate'] : "-"; ?></td>

                <!-- Fine Amount -->
                <td class="amount">
                    <?php 
                    if($row['FineAmount'] > 0){
                        echo "₹" . $row['FineAmount'];
                    } else {
                        echo "₹0";
                    }
                    ?>
                </td>

                <!-- Fine Status -->
                <td>
                    <?php 
                    if($row['FineStatus'] == 'Paid'){
                        echo "<span class='paid'>Paid</span>";
                    } elseif($row['FineStatus'] == 'Unpaid'){
                        echo "<span class='unpaid'>Unpaid</span>";
                    } else {
                        echo "<span class='no-fine'>No Fine</span>";
                    }
                    ?>
                </td>
            </tr>

            <?php 
                }
            }else{
                echo "<tr><td colspan='7'>No fine records found</td></tr>";
            }
            ?>

        </table>

    </div>

</body>
</html>