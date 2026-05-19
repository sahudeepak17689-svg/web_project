
<?php
session_start();
include("../db/config.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$bus_id = isset($_GET['bus_id']) ? intval($_GET['bus_id']) : 0;

if($bus_id <= 0){
    die("Invalid Bus ID");
}

$sql_bus = "SELECT * FROM buses WHERE id='$bus_id'";
$result_bus = $conn->query($sql_bus);

if(!$result_bus || $result_bus->num_rows == 0){
    die("Bus not found");
}

$bus = $result_bus->fetch_assoc();
$ticket_price = isset($bus['price']) ? $bus['price'] : 500;

$bookedSeats = [];
$sql_booked = "SELECT seat_number FROM bookings WHERE bus_id='$bus_id'";
$result_booked = $conn->query($sql_booked);

if($result_booked && $result_booked->num_rows > 0){
    while($row = $result_booked->fetch_assoc()){
        $bookedSeats[] = $row['seat_number'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Seat</title>

    <style>
        *{
            box-sizing:border-box;
        }

        body{
            margin:0;
            font-family:Arial, sans-serif;
            background: linear-gradient(to right, #74ebd5, #9face6);
            padding:30px 15px;
        }

        .container{
            width:800px;
            max-width:100%;
            margin:auto;
            background:#f5f5f5;
            border-radius:20px;
            padding:30px;
            box-shadow:0 10px 30px rgba(0,0,0,0.18);
            text-align:center;
        }

        h2{
            margin:0 0 10px;
            color:#222;
        }

        .route{
            font-size:20px;
            margin-bottom:20px;
        }

        .top-info{
            display:flex;
            justify-content:center;
            gap:25px;
            flex-wrap:wrap;
            margin-bottom:25px;
        }

        .top-info div{
            font-size:16px;
        }

        .legend{
            display:inline-block;
            width:18px;
            height:18px;
            border-radius:4px;
            margin-right:6px;
            vertical-align:middle;
        }

        .available{ background:#d1d5db; }
        .selected{ background:#22c55e; }
        .booked-color{ background:#ef4444; }

        .passenger-box{
            margin-bottom:25px;
        }

        .passenger-box h3{
            margin-bottom:15px;
        }

        .passenger-box input{
            width:220px;
            max-width:95%;
            padding:10px 12px;
            margin:6px;
            border:1px solid #999;
            border-radius:7px;
            font-size:15px;
        }

        .bus-layout{
            display:flex;
            justify-content:center;
            align-items:flex-start;
            gap:20px;
            margin:25px 0;
        }

        .driver{
            font-weight:bold;
            font-size:20px;
            margin-top:25px;
        }

        .bus{
            display:grid;
            grid-template-columns:repeat(5, 70px);
            gap:12px;
            background:#e5e7eb;
            padding:22px;
            border:3px solid #333;
            border-radius:22px;
        }

        .path{
            width:70px;
            height:55px;
        }

        .seat{
            width:70px;
            height:55px;
            display:block;
            position:relative;
            cursor:pointer;
        }

        .seat input{
            display:none;
        }

        .seat span{
            width:100%;
            height:100%;
            display:flex;
            justify-content:center;
            align-items:center;
            background:#d1d5db;
            border:2px solid #9ca3af;
            border-radius:12px 12px 8px 8px;
            font-size:15px;
            font-weight:bold;
            color:#111;
        }

        .seat input:checked + span{
            background:#22c55e;
            color:#fff;
            border-color:#15803d;
        }

        .booked span{
            background:#ef4444 !important;
            color:#fff !important;
            border-color:#b91c1c !important;
            cursor:not-allowed;
        }

        .summary-box{
            background:#fff;
            padding:16px;
            border-radius:10px;
            text-align:left;
            line-height:1.8;
            margin-top:20px;
        }

        .btn{
            margin-top:20px;
            background:#2563eb;
            color:#fff;
            border:none;
            padding:12px 20px;
            border-radius:8px;
            font-size:16px;
            cursor:pointer;
        }

        @media (max-width:768px){
            .bus-layout{
                flex-direction:column;
                align-items:center;
            }

            .bus{
                grid-template-columns:repeat(5, 55px);
                gap:8px;
                padding:15px;
            }

            .seat, .path{
                width:55px;
                height:45px;
            }

            .seat span{
                font-size:12px;
            }

            .driver{
                margin-top:0;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2><?php echo $bus['bus_name']; ?> - Seat Selection</h2>
    <p class="route"><?php echo $bus['source']; ?> → <?php echo $bus['destination']; ?></p>

    <div class="top-info">
        <div><span class="legend available"></span> Available</div>
        <div><span class="legend selected"></span> Selected</div>
        <div><span class="legend booked-color"></span> Booked</div>
    </div>

    <form action="book_ticket.php" method="POST" onsubmit="return validateBooking()">
        <input type="hidden" name="bus_id" value="<?php echo $bus_id; ?>">
        <input type="hidden" name="ticket_price" id="ticket_price" value="<?php echo $ticket_price; ?>">

        <div class="passenger-box">
            <h3>Passenger Details</h3>
            <input type="text" name="username" id="username" placeholder="Passenger Name" required>
            <input type="number" name="age" id="age" placeholder="Age" required>
            <input type="text" name="mobile" id="mobile" placeholder="Phone Number" maxlength="10" required>
            <input type="text" name="gender" id="gender" placeholder="gender" required>
        </div>

        <div class="bus-layout">
            <div class="driver">Driver</div>

            <div class="bus">
                <?php
                $seats = [
                    "A1","A2","","A3","A4",
                    "B1","B2","","B3","B4",
                    "C1","C2","","C3","C4",
                    "D1","D2","","D3","D4",
                    "E1","E2","","E3","E4",
                    "F1","F2","","F3","F4",
                    "G1","G2","","G3","G4",
                    "H1","H2","","H3","H4"
                ];

                foreach($seats as $seat){
                    if($seat == ""){
                        echo "<div class='path'></div>";
                    } else {
                        $isBooked = in_array($seat, $bookedSeats);
                        ?>
                        <label class="seat <?php echo $isBooked ? 'booked' : ''; ?>">
                            <input 
                                type="checkbox" 
                                name="seat[]" 
                                value="<?php echo $seat; ?>" 
                                <?php echo $isBooked ? 'disabled' : ''; ?>
                                onchange="updatePrice()"
                            >
                            <span><?php echo $seat; ?></span>
                        </label>
                        <?php
                    }
                }
                ?>
            </div>
        </div>

        <div class="summary-box">
            <p><strong>Ticket Price:</strong> ₹<span id="singlePrice"><?php echo $ticket_price; ?></span> / seat</p>
            <p><strong>Selected Seats:</strong> <span id="seatList">None</span></p>
            <p><strong>Total Seats:</strong> <span id="seatCount">0</span></p>
            <p><strong>Total Price:</strong> ₹<span id="totalPrice">0</span></p>
        </div>

        <button type="submit" class="btn">Book Ticket</button>
    </form>
</div>

<script>
function updatePrice() {
    let checkboxes = document.querySelectorAll("input[name='seat[]']:checked");
    let price = parseInt(document.getElementById("ticket_price").value) || 0;
    let seatNames = [];

    checkboxes.forEach(function(box){
        seatNames.push(box.value);
    });

    document.getElementById("seatCount").innerText = checkboxes.length;
    document.getElementById("totalPrice").innerText = checkboxes.length * price;
    document.getElementById("seatList").innerText = seatNames.length ? seatNames.join(", ") : "None";
}

function validateBooking(){
    let username = document.getElementById("username").value.trim();
    let age = document.getElementById("age").value.trim();
    let mobile = document.getElementById("mobile").value.trim();
    let seats = document.querySelectorAll("input[name='seat[]']:checked");

    if(username === "" || age === "" || mobile === ""){
        alert("Please fill all passenger details");
        return false;
    }

    if(age <= 0){
        alert("Please enter valid age");
        return false;
    }

    if(mobile.length != 10 || isNaN(mobile)){
        alert("Please enter valid 10 digit phone number");
        return false;
    }

    if(seats.length === 0){
        alert("Please select at least one seat");
        return false;
    }

    return true;
}
</script>

</body>
</html>