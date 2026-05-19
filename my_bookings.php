<?php
session_start();
include("../db/config.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT bookings.id, bookings.username, bookings.age, bookings.gender, bookings.mobile,
               bookings.seat_number, bookings.booking_date, buses.bus_name
        FROM bookings
        LEFT JOIN buses ON bookings.bus_id = buses.id
        WHERE bookings.user_id = '$user_id'
        ORDER BY bookings.id DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Bookings</title>
    <style>
        body{
            font-family: Arial, sans-serif;
            background:#f2f2f2;
            margin:0;
            padding:30px;
        }
        h2{
            text-align:center;
            margin-bottom:20px;
        }
        table{
            width:100%;
            border-collapse:collapse;
            background:white;
            box-shadow:0 0 10px rgba(0,0,0,0.1);
        }
        th, td{
            padding:12px;
            border:1px solid #ddd;
            text-align:center;
        }
        th{
            background:#007bff;
            color:white;
        }
        tr:nth-child(even){
            background:#f9f9f9;
        }
        .back{
            display:inline-block;
            margin-bottom:20px;
            padding:10px 15px;
            background:#007bff;
            color:white;
            text-decoration:none;
            border-radius:6px;
        }
    </style>
</head>
<body>

<a href="dashboard.php" class="back">Back</a>

<h2>My Bookings</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Age</th>
        <th>Gender</th>
        <th>Mobile</th>
        <th>Bus Name</th>
        <th>Seat</th>
        <th>Booking Date</th>
    </tr>

    <?php
    if($result && $result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            echo "<tr>
                    <td>".$row['id']."</td>
                    <td>".$row['username']."</td>
                    <td>".$row['age']."</td>
                    <td>".$row['gender']."</td>
                    <td>".$row['mobile']."</td>
                    <td>".$row['bus_name']."</td>
                    <td>".$row['seat_number']."</td>
                    <td>".$row['booking_date']."</td>
                  </tr>";
        }
    }else{
        echo "<tr><td colspan='8'>No bookings found</td></tr>";
    }
    ?>
</table>

</body>
</html>