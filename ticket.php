<?php
session_start();
include("../db/config.php");

$user_id=$_SESSION['user_id'];
$bus_id=$_GET['bus_id'];

$sql="SELECT buses.bus_name, bookings.seat_number, bookings.booking_date
FROM bookings
JOIN buses ON bookings.bus_id=buses.id
WHERE bookings.user_id='$user_id' AND bookings.bus_id='$bus_id'";

$result=$conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>Bus Ticket</title>

<style>

body{
font-family:Arial;
background:#f2f2f2;
text-align:center;
}

.ticket{
width:400px;
margin:50px auto;
padding:20px;
background:white;
border-radius:10px;
box-shadow:0 0 10px rgba(0,0,0,0.2);
}

h2{
color:#2c3e50;
}

p{
font-size:18px;
}

button{
padding:10px 20px;
background:#27ae60;
color:white;
border:none;
border-radius:5px;
cursor:pointer;
}

button:hover{
background:#1e8449;
}

</style>

</head>

<body>

<div class="ticket">

<h2>Bus Ticket</h2>

<?php while($row=$result->fetch_assoc()){ ?>

<p><b>Bus:</b> <?php echo $row['bus_name']; ?></p>

<p><b>Seat:</b> <?php echo $row['seat_number']; ?></p>

<p><b>Date:</b> <?php echo $row['booking_date']; ?></p>

<hr>

<?php } ?>

<button onclick="window.print()">Print Ticket</button>

</div>

</body>
</html>