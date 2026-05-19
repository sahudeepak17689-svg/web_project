<?php
session_start();
include("../db/config.php");

if(!isset($_GET['booking_id'])){
    die("Booking ID missing");
}

$booking_id = $_GET['booking_id'];

$sql = "SELECT bookings.*, buses.bus_name
        FROM bookings
        JOIN buses ON bookings.bus_id = buses.id
        WHERE bookings.id = '$booking_id'";

$result = $conn->query($sql);

if($result->num_rows > 0){
    $row = $result->fetch_assoc();
}else{
    die("Ticket not found");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Print Ticket</title>
</head>
<body>

<h1>Bus Ticket</h1>

<p><strong>Name:</strong> <?php echo $row['username']; ?></p>
<p><strong>Age:</strong> <?php echo $row['age']; ?></p>
<p><strong>Gender:</strong> <?php echo $row['gender']; ?></p>
<p><strong>Mobile:</strong> <?php echo $row['mobile']; ?></p>
<p><strong>Bus Name:</strong> <?php echo $row['bus_name']; ?></p>
<p><strong>Seat:</strong> <?php echo $row['seat_number']; ?></p>
<p><strong>Booking Date:</strong> <?php echo $row['booking_date']; ?></p>

<button onclick="window.print()">Print Ticket</button>

</body>
</html>