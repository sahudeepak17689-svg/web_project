<?php
session_start();
include("../db/config.php");

$message = "";

if(isset($_POST['login'])){

    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $row['id'];
        header("Location: dashboard.php");
        exit();
    }else{
        $message = "Invalid User Login";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Login</title>
    <link rel="stylesheet" href="Login.css?v=2">
</head>
<body>

<div class="container">
    <h2>LOGIN USER</h2>

    <?php if($message != ""){ ?>
        <p><?php echo $message; ?></p>
    <?php } ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>

    <a href="register.php">Create New Account</a>
</div>

</body>
</html>