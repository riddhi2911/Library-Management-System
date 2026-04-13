<?php
include("Connection.php");
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>LMS Dashboard</title>

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

.logo { text-align: center; padding: 20px; }
.logo img { width: 80px; }

.main { margin-left: 250px; padding: 20px; }

.header {
    background: #145a6f;
    color: white;
    padding: 15px;
    border-radius: 5px;
}

.upload-box {
    border: 2px dashed #ccc;
    border-radius: 12px;
    background: #f8f9fa;
    padding: 20px;
    text-align: center;
}

.footer {
    background-color: #0d3b4c;
    padding: 10px;
    text-align: center;
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
    <a href="AdminSettings.php"><i class="fas fa-cog me-2"></i> Settings</a>
    <a href="AdminLogout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
</div>

<!-- MAIN -->
<div class="main">

<div class="header mb-4">
    <h4>Librarian Add New Book Panel</h4>
</div>

<div class="container-fluid">

<div class="card p-4" style="border-radius:15px;">
<h4 class="text-center mb-4">Add New Book</h4>

<form method="POST">

<div class="row">

    <!-- IMAGE LINK (NEW) -->
    <div class="col-md-6 mb-3">
        <label>Book Image Link</label>
        <input type="text" name="book_link" class="form-control" placeholder="Paste image URL here (https://...)">
    </div>

    <!-- TITLE -->
    <div class="col-md-6 mb-3">
        <label>Title</label>
        <input type="text" name="title" class="form-control" required>
    </div>

</div>

<div class="row">

    <!-- PUBLISHER -->
    <div class="col-md-6 mb-3">
        <label>Publisher</label>
        <input type="text" name="publisher" class="form-control">
    </div>

    <!-- AUTHOR -->
    <div class="col-md-6 mb-3">
        <label>Author</label>
        <input type="text" name="author" class="form-control">
    </div>

</div>

<div class="row">

    <!-- CATEGORY -->
    <div class="col-md-6 mb-3">
        <label>Category</label>
        <select name="category" id="categorySelect" class="form-control" onchange="toggleCategoryInput()">
            <option value="">Select Category</option>

            <?php
            $cat = mysqli_query($conn, "SELECT * FROM Categories");
            while($c = mysqli_fetch_assoc($cat)) {
                echo "<option value='{$c['CategoryId']}'>{$c['CategoryName']}</option>";
            }
            ?>

            <option value="new">+ Add New Category</option>
        </select>

        <input type="text" name="new_category" id="newCategoryInput"
        class="form-control mt-2" placeholder="Enter new category" style="display:none;">
    </div>

    <!-- QUANTITY -->
    <div class="col-md-6 mb-3">
        <label>Quantity</label>
        <input type="number" name="quantity" class="form-control">
    </div>

</div>

<div class="row">

    <!-- YEAR -->
    <div class="col-md-6 mb-3">
        <label>Publish Year</label>
        <input type="number" name="year" class="form-control">
    </div>

    <!-- RATING -->
    <div class="col-md-6 mb-3">
        <label>Rating</label>
        <input type="number" name="rating" class="form-control" min="1" max="5" step="0.1">
    </div>

</div>

<!-- DESCRIPTION -->
<div class="mb-3">
    <label>Description</label>
    <textarea name="description" class="form-control" rows="3"></textarea>
</div>

<div class="text-end">
    <a href="AdminManageBook.php" class="btn btn-secondary">Cancel</a>
    <button type="submit" class="btn btn-success">Add Book</button>
</div>

</form>

</div>
</div>

</div>

<?php
if($_SERVER['REQUEST_METHOD'] == 'POST') {

    $title = $_POST['title'];
    $author = $_POST['author'];
    $category = $_POST['category'];
    $newCategory = $_POST['new_category'];
    $quantity = $_POST['quantity'];
    $year = $_POST['year'];
    $publisher = $_POST['publisher'];
    $rating = $_POST['rating'];
    $description = $_POST['description'];

    // IMAGE LINK (NEW)
    $book_link = $_POST['book_link'];

    // New category insert
    if($category == "new" && !empty($newCategory)) {
        $stmtCat = $conn->prepare("INSERT INTO Categories (CategoryName) VALUES (?)");
        $stmtCat->bind_param("s", $newCategory);
        $stmtCat->execute();
        $category = $conn->insert_id;
    }

    // INSERT BOOK
    $stmt = $conn->prepare("
        INSERT INTO Book 
        (Title, Author, CategoryId, Publisher, Year, Quantity, BookLink, Description, Rate) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "ssisisssd",
        $title,
        $author,
        $category,
        $publisher,
        $year,
        $quantity,
        $book_link,
        $description,
        $rating
    );

    if($stmt->execute()){
        echo "<script>alert('Book Added Successfully'); window.location='AdminManageBook.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<script>
function toggleCategoryInput() {
    let select = document.getElementById("categorySelect");
    let input = document.getElementById("newCategoryInput");

    input.style.display = (select.value === "new") ? "block" : "none";
}
</script>

</body>
</html>