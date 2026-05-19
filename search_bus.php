<?php
include("../db/config.php");

$source = "";
$destination = "";

if(isset($_GET['search'])){
    $source = $_GET['source'];
    $destination = $_GET['destination'];

    $sql = "SELECT * FROM buses WHERE source LIKE '%$source%' AND destination LIKE '%$destination%'";
}else{
    $sql = "SELECT * FROM buses";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Search Bus</title>
<link rel="stylesheet" href="search_bus.css">
</head>

<body>

<h1>BOOK YOUR BUS</h1>

<div class="container">

<h2>Available Buses</h2>

<form method="GET">
<label>Source:</label>
<input type="text" name="source" placeholder="Enter Source" value="<?php echo $source; ?>">

<label>Destination:</label>
<input type="text" name="destination" placeholder="Enter Destination" value="<?php echo $destination; ?>">

<button type="submit" name="search">Search</button>
</form>

<br>

<?php
if($result->num_rows > 0){
?>

<table border="1">
<tr>
<th>Bus</th>
<th>Source</th>
<th>Destination</th>
<th>Date</th>
<th>Departure Time</th>
<th>Arrival Time</th>
<th>Price</th>
<th>Action</th>
</tr>

<?php while($row = $result->fetch_assoc()){ ?>

<tr>
<td><?php echo $row['bus_name']; ?></td>
<td><?php echo $row['source']; ?></td>
<td><?php echo $row['destination']; ?></td>
<td><?php echo $row['travel_date']; ?></td>
<td><?php echo $row['departure_time']; ?></td>
<td><?php echo $row['arrival_time']; ?></td>
<td><?php echo $row['price']; ?></td>
<td>
<a href="select_seat.php?bus_id=<?php echo $row['id']; ?>">Select Seat</a>
</td>
</tr>

<?php } ?>

</table>

<?php
}else{
echo "<h3 style='color:red;'>No buses available for this route.</h3>";
}
?>

</div>

</body>
</html>