<?php
include("Connection.php");

$emailErr = $passwordErr = $successMsg = "";

if(isset($_POST['reset']))
{
    $email = trim($_POST['Email']);
    $password = trim($_POST['Password']);

    if(empty($email)){
        $emailErr = "Email is required!";
    }

    if(empty($password)){
        $passwordErr = "Password is required!";
    }

    if(empty($emailErr) && empty($passwordErr))
    {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $query = "UPDATE Users SET Password=? WHERE Email=?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ss", $hashed_password, $email);

        if(mysqli_stmt_execute($stmt))
        {
            $successMsg = "Password Updated Successfully!";
        }
        else
        {
            $successMsg = "Error updating password!";
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Forgot Password</title>

<style>
    body {
        margin: 0;
        font-family: 'Segoe UI', sans-serif;
        background: url('library.jpg') no-repeat center/cover;
        height: 100vh;
    }

    body::before {
        content:"";
        position: fixed;
        width:100%;
        height:100%;
        background: rgba(0,0,0,0.5);
    }

    /* Center Box */
    .container {
        position: relative;
        z-index: 2;
        height: 100vh;
        display:flex;
        justify-content:center;
        align-items:center;
    }

    .box {
        background: rgba(255,255,255,0.95);
        padding:30px;
        width:350px;
        border-radius:10px;
        text-align:center;
    }

    .input-group {
        border:1px solid #ddd;
        padding:10px;
        margin:10px 0;
        border-radius:5px;
    }

    .input-group input {
        width:100%;
        border:none;
        outline:none;
    }

    button {
        width:100%;
        padding:10px;
        background:#e74c3c;
        border:none;
        color:white;
        border-radius:5px;
        cursor:pointer;
    }

    button:hover {
        background:#c0392b;
    }

    span {
        color:red;
        font-size:13px;
    }

    .success {
        color:green;
    }
</style>
</head>

<body>

    <div class="container">
        <div class="box">
            <h2>Reset Password</h2>

            <form method="POST">

                <div class="input-group">
                    <input type="email" name="Email" placeholder="Enter your Email">
                </div>
                <span><?php echo $emailErr; ?></span>

                <div class="input-group">
                    <input type="password" name="Password" placeholder="New Password">
                </div>
                <span><?php echo $passwordErr; ?></span>

                <button name="reset">Update Password</button>

                <p class="success"><?php echo $successMsg; ?></p>

                <br>
                <a href="UserLogin.php">Back to Login</a>

            </form>
        </div>
    </div>

</body>
</html>