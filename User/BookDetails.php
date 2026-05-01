<?php
session_start();
include("Connection.php");

if(!isset($_GET['id'])){
    header("Location: UserIndex.php");
    exit();
}

$book_id = $_GET['id'];

// Fetch book
$query = "SELECT * FROM Book WHERE BookId='$book_id'";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) == 0){
    echo "Book not found!";
    exit();
}

$book = mysqli_fetch_assoc($result);

// ✅ Fetch user status
$user_id = $_SESSION['UserId'];

$user_result = mysqli_query($conn, "
    SELECT Status FROM Users WHERE UserId='$user_id'
");

$user = mysqli_fetch_assoc($user_result);
$user_status = strtolower($user['Status']);

// Image fix
$image = "images/default.jpg";
if(!empty($book['BookLink'])){
    if(filter_var($book['BookLink'], FILTER_VALIDATE_URL)){
        $image = $book['BookLink'];
    } else {
        $image = "uploads/" . $book['BookLink'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Book Details</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    font-family: Arial;
    background:#f4f4f4;
}

.container{
    width:70%;
    margin:50px auto;
    background:white;
    padding:20px;
    border-radius:10px;
    box-shadow:0 0 10px rgba(0,0,0,0.2);
}

.book-box{
    display:flex;
    gap:20px;
}

.book-box img{
    width:250px;
    height:300px;
    object-fit:contain;
}

.btn{
    display:inline-block;
    margin-top:15px;
    padding:10px 15px;
    background:#2ecc71;
    color:white;
    text-decoration:none;
    border-radius:5px;
}
</style>
</head>

<body>

<!-- POPUP MESSAGE -->
<?php if($user_status == "block"){ ?>
<script>
    alert("Access denied! Your account is blocked due to pending fine. Please clear it to continue.");
</script>
<?php } ?>

<div class="container">

    <div class="book-box">
        <img src="<?= $image; ?>" onerror="this.src='images/default.jpg';">

        <div class="details">
            <h2><?= $book['Title']; ?></h2>
            <p><b>Author:</b> <?= $book['Author']; ?></p>
            <p><b>Description:</b> <?= $book['Description']; ?></p>
            <p><b>Publisher:</b> <?= $book['Publisher']; ?></p>
            <p><b>Year:</b> <?= $book['Year']; ?></p>

            <!--Rating -->
            <p>
            <?php
            $rating = isset($book['Rate']) ? $book['Rate'] : 0;

            $full = floor($rating);
            $half = ($rating - $full >= 0.5) ? 1 : 0;
            $empty = 5 - ($full + $half);

            for($i = 0; $i < $full; $i++){
                echo "<span style='color:gold;'>★</span>";
            }

            if($half){
                echo "<span style='color:gold;'>⯨</span>";
            }

            for($i = 0; $i < $empty; $i++){
                echo "<span style='color:#ccc;'>☆</span>";
            }

            echo " <small>(" . $rating . "/5)</small>";
            ?>
            </p>

            <!-- BUTTON LOGIC -->
            <?php if($user_status == "block"){ ?>

                <button class="btn" style="background:gray; cursor:not-allowed;" disabled>
                    Add to Cart
                </button>

            <?php } else { ?>

                <a href="AddToCart.php?id=<?= $book['BookId']; ?>" class="btn">
                    Add to Cart
                </a>

            <?php } ?>

        </div>
    </div>

</div>

</body>
</html>