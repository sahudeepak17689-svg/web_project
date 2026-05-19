<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
   <link rel="stylesheet" href="register.css">

</head>
<body>
    

<?php
include("../db/config.php");

if(isset($_POST['register'])){
$name=$_POST['name'];
$email=$_POST['email'];
$password=md5($_POST['password']);

$sql="INSERT INTO users(name,email,password) VALUES('$name','$email','$password')";
$conn->query($sql);
echo "Registered Successfully";
}
?>

<form method="POST">
    <h2>REGISTER USER<h2>
<input type="text" name="name" placeholder="Name" required>
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Password" required>
<button name="register">Register</button>
</form>
</body>
</html>
