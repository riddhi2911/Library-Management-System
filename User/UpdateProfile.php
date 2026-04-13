<?php
session_start();
include('Connection.php');

if (!isset($_SESSION['UserId'])) {
    header("Location: UserLogin.php");
    exit();
}

if (isset($_POST['update'])) {

    $userId = $_SESSION['UserId'];

    $name = $_POST['Name'];
    $email = $_POST['Email'];
    $phone = $_POST['PhoneNo'];
    $address = $_POST['Address'];
    $city = $_POST['City'];
    $dob = $_POST['DOB'];

    //  Default (no image update)
    $imageQuery = "";

    // Check if image uploaded
    if(isset($_FILES['profile_image']) && $_FILES['profile_image']['name'] != ""){

        $ProfileImage = $_FILES['profile_image']['name'];
        $tmpName = $_FILES['profile_image']['tmp_name'];

        $folder = "uploads/" . time() . "_" . $ProfileImage;

        move_uploaded_file($tmpName, $folder);

        //  Add image in query
        $imageQuery = ", profile_image = '$folder'";
    }

    //  FIXED QUERY (comma added properly)
    $query = "UPDATE Users SET 
                Name='$name',
                Email='$email',
                PhoneNo='$phone',
                Address='$address',
                City='$city',
                DOB='$dob'
                $imageQuery
              WHERE UserId='$userId'";

    mysqli_query($conn, $query);

    header("Location: UserProfile.php");
    exit();
}
?>