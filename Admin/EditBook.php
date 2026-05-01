<?php
include("Connection.php");
session_start();

$id = intval($_GET['id']);
$result = mysqli_query($conn, "SELECT * FROM Book WHERE BookId=$id");
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Book</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    body { margin:0; }

    /* Sidebar */
    .sidebar {
        width:250px;
        min-height:100vh;
        background:#0d3b4c;
        color:white;
        position:fixed;
    }

    .sidebar a {
        color:white;
        display:block;
        padding:12px;
        text-decoration:none;
    }

    .sidebar a:hover { background:#145a6f; }

    .logo { text-align:center; padding:20px; }
    .logo img { width:80px; }

    .main {
        margin-left:250px;
        padding:20px;
    }

    .header {
        background:#145a6f;
        color:white;
        padding:15px;
        border-radius:5px;
    }
</style>
</head>

<body>

    <!-- SIDEBAR -->
    <div class="sidebar">

        <div class="logo">
            <img src="logo.jpeg">
            <h5><?php echo (!empty($_SESSION['site_name'])) ? $_SESSION['site_name'] : 'Book Spark'; ?></h5>
        </div>

        <a href="AdminIndex.php"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
        <a href="AdminManageBook.php"><i class="fas fa-book me-2"></i> Manage Book</a>
        <a href="AdminManageUser.php"><i class="fas fa-users me-2"></i> Manage User</a>
        <a href="AdminRequestBook.php"><i class="fas fa-book-open me-2"></i> Requested Book</a>
        <a href="AdminIssueBook.php"><i class="fas fa-arrow-up me-2"></i> Issued Books</a>
        <a href="AdminReturnBook.php"><i class="fas fa-arrow-down me-2"></i> Return Book</a>
        <a href="AdminFine.php"><i class="fas fa-money-bill-wave me-2"></i> Fine</a>
        <a href="AdminLogout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>

    </div>

    <!-- MAIN -->
    <div class="main">

        <div class="header mb-4">
            <h4>Edit Book Panel</h4>
        </div>

        <div class="container mt-4">

            <div class="card p-4">

                <h4 class="text-center mb-4">Edit Book</h4>

                <form method="POST">

                    <div class="row">

                        <!-- IMAGE LINK (NEW) -->
                        <div class="col-md-4 text-center">

                            <?php
                            $img = $row['BookLink'];

                            if(!filter_var($img, FILTER_VALIDATE_URL)){
                                $img = "uploads/".$img;
                            }
                            ?>

                            <img src="<?php echo $img; ?>" 
                                style="height:150px; object-fit:contain;" class="mb-3">

                            <label>Image Link</label>
                            <input type="text" name="book_link" class="form-control"
                                value="<?php echo $row['BookLink']; ?>">
                        </div>

                        <!-- DETAILS -->
                        <div class="col-md-8">

                            <div class="mb-3">
                                <label>Title</label>
                                <input type="text" name="title" class="form-control"
                                    value="<?php echo $row['Title']; ?>">
                            </div>

                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label>Author</label>
                                    <input type="text" name="author" class="form-control"
                                        value="<?php echo $row['Author']; ?>">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Publisher</label>
                                    <input type="text" name="publisher" class="form-control"
                                        value="<?php echo $row['Publisher']; ?>">
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label>Category</label>
                                    <select name="category" class="form-control">

                                        <?php
                                        $cat = mysqli_query($conn, "SELECT * FROM Category");
                                        while($c = mysqli_fetch_assoc($cat)) {
                                            $selected = ($c['CategoryId'] == $row['CategoryId']) ? "selected" : "";
                                            echo "<option value='{$c['CategoryId']}' $selected>{$c['CategoryName']}</option>";
                                        }
                                        ?>

                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Quantity</label>
                                    <input type="number" name="quantity" class="form-control"
                                        value="<?php echo $row['Quantity']; ?>">
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label>Year</label>
                                    <input type="number" name="year" class="form-control"
                                        value="<?php echo $row['Year']; ?>">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Rating</label>
                                    <input type="number" name="rating" class="form-control"
                                        min="1" max="5" step="0.1"
                                        value="<?php echo $row['Rate']; ?>">
                                </div>

                            </div>

                            <div class="mb-3">
                                <label>Description</label>
                                <textarea name="description" class="form-control"><?php echo $row['Description']; ?></textarea>
                            </div>

                        </div>
                    </div>

                    <div class="text-end">
                        <a href="AdminManageBook.php" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <?php
    // UPDATE LOGIC
    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        $title = $_POST['title'];
        $author = $_POST['author'];
        $publisher = $_POST['publisher'];
        $category = $_POST['category'];
        $quantity = $_POST['quantity'];
        $year = $_POST['year'];
        $rating = $_POST['rating'];
        $description = $_POST['description'];

        // NEW IMAGE LINK
        $book_link = $_POST['book_link'];

        if(empty($book_link)){
            $book_link = $row['BookLink'];
        }

        $stmt = $conn->prepare("
            UPDATE Book 
            SET Title=?, Author=?, CategoryId=?, Publisher=?, Year=?, Quantity=?, BookLink=?, Description=?, Rate=? 
            WHERE BookId=?
        ");

        $stmt->bind_param(
            "ssisisssdi",
            $title,
            $author,
            $category,
            $publisher,
            $year,
            $quantity,
            $book_link,
            $description,
            $rating,
            $id
        );

        $stmt->execute();

        echo "<script>alert('Book Updated Successfully'); window.location='AdminManageBook.php';</script>";
    }
    ?>

</body>
</html>