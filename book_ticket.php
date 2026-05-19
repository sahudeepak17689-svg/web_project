
<?php
session_start();
include("../db/config.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

if(isset($_POST['seat']) && !empty($_POST['seat'])){

    $user_id = $_SESSION['user_id'];
    $bus_id = intval($_POST['bus_id']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $age = intval($_POST['age']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $ticket_price = intval($_POST['ticket_price']);
    $seats = $_POST['seat'];

    $total_price = count($seats) * $ticket_price;
    $inserted_seats = [];

    foreach($seats as $seat){
        $seat = mysqli_real_escape_string($conn, $seat);

        $check_sql = "SELECT * FROM bookings WHERE bus_id='$bus_id' AND seat_number='$seat'";
        $check_result = $conn->query($check_sql);

        if(!$check_result){
            die("Check query failed: " . $conn->error);
        }

        if($check_result->num_rows == 0){

            $insert_sql = "INSERT INTO bookings(user_id, bus_id, username, age, mobile, seat_number)
                           VALUES('$user_id', '$bus_id', '$username', '$age', '$mobile', '$seat')";

            if($conn->query($insert_sql) === TRUE){
                $inserted_seats[] = $seat;
            } else {
                die("Insert failed: " . $conn->error);
            }
        }
    }

    if(count($inserted_seats) > 0){
        echo "<!DOCTYPE html>
        <html>
        <head>
            <title>Ticket Confirmation</title>
            <style>
                body{
                    font-family:Arial, sans-serif;
                    background:#f1f5f9;
                    padding:30px;
                }
                .ticket{
                    max-width:500px;
                    margin:auto;
                    background:white;
                    padding:25px;
                    border-radius:15px;
                    box-shadow:0 8px 25px rgba(0,0,0,0.15);
                }
                h2{
                    text-align:center;
                    color:green;
                }
                p{
                    font-size:17px;
                    margin:10px 0;
                }
                .btn{
                    display:block;
                    text-align:center;
                    background:#2563eb;
                    color:white;
                    padding:12px;
                    text-decoration:none;
                    border-radius:8px;
                    margin-top:20px;
                }
            </style>
        </head>
        <body>
            <div class='ticket'>
                <h2>Ticket Booked Successfully</h2>
                <p><strong>Name:</strong> $username</p>
                <p><strong>Age:</strong> $age</p>
                <p><strong>Phone:</strong> $mobile</p>
                <p><strong>Seats:</strong> ".implode(", ", $inserted_seats)."</p>
                <p><strong>Price Per Seat:</strong> ₹$ticket_price</p>
                <p><strong>Total Price:</strong> ₹$total_price</p>
                <a class='btn' href='my_bookings.php'>View My Bookings</a>
            </div>
        </body>
        </html>";
    } else {
        echo "All selected seats are already booked or insert failed.";
    }

} else {
    echo "No seat selected";
}
?>