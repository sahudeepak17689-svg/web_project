<?php
session_start();
if(!isset($_SESSION['user_id'])){
header("Location: login.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>User Dashboard</title>
<link rel="stylesheet" href="dashboard.css">




</head>

<body>

<div class="main">

<div class="header">
<h1>Welcome to Bus Reservation System</h1>
</div>

<div class="dashboard-box">

<div class="box">
<h3>Search Bus</h3>
<p>Find available buses and book tickets.</p>
<a href="search_bus.php">Go</a>
</div>

<div class="box">
<h3>My Bookings</h3>
<p>Check your booked bus tickets.</p>
<a href="my_bookings.php">View</a>
</div>

</div>

</div>

</body>
</html>