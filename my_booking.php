<?php
session_start();
include("../db/config.php");

$user_id=$_SESSION['user_id'];

$sql="SELECT buses.bus_name, bookings.seat_number, bookings.booking_date
FROM bookings
JOIN buses ON bookings.bus_id=buses.id
WHERE bookings.user_id='$user_id'";

$result=$conn->query($sql);
?>
<link rel="stylesheet" href="my_bookings.css">

<h2>My Bookings</h2>

<table border="1">
<tr>
<th>Bus</th>
<th>Seat</th>
<th>Date</th>
</tr>

<?php while($row=$result->fetch_assoc()){ ?>
<tr>
<td><?php echo $row['bus_name']; ?></td>
<td><?php echo $row['seat_number']; ?></td>
<td><?php echo $row['booking_date']; ?></td>
</tr>
<?php } ?>
</table>