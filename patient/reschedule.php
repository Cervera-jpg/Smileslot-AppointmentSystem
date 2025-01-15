<?php
session_start();
include("../connection.php");

if (!isset($_SESSION["user"]) || $_SESSION['usertype'] != 'p') {
    header("location: ../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("location: appointment.php");
    exit();
}

$booking_id = $_GET['id'];

// If form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_date = $_POST['new_date'];
    $new_time = $_POST['new_time'];
    
    // Update the booking
    $sql = "UPDATE booking SET 
            DATE = ?, 
            time_slot = ?,
            status = 'Pending Reschedule'
            WHERE id = ?";
            
    $stmt = $database->prepare($sql);
    $stmt->bind_param("ssi", $new_date, $new_time, $booking_id);
    
    if ($stmt->execute()) {
        header("location: appointment.php?reschedule=success");
    } else {
        header("location: appointment.php?reschedule=failed");
    }
    exit();
}

// Get current booking details
$sql = "SELECT * FROM booking WHERE id = ?";
$stmt = $database->prepare($sql);
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();

if (!$booking) {
    header("location: appointment.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reschedule Appointment</title>
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/finalmain.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <style>
        .reschedule-container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: 40px auto;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }

        .form-group input[type="date"],
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .button-container {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn-reschedule {
            background-color: #912bbc;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            font-size: 16px;
        }

        .btn-cancel {
            background-color: #f0f0f0;
            color: #333;
        }

        .btn-reschedule:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        .page-title {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 24px;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <div class="logo">
                <a href="index.php" class="logo-link">
                    <img src="../img/Rimas_logo_png.png" alt="SmileSlot Logo" class="logo-img" />
                    <span class="logo-title">SMILESLOT </span>
                    <span class="logo-sub"> | RIMAS DENTAL CLINIC</span>
                </a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="reschedule-container">
            <h2 class="page-title">Reschedule Appointment</h2>
            <form action="" method="POST">
                <div class="form-group">
                    <label>New Date:</label>
                    <input type="date" name="new_date" required min="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="form-group">
                    <label>New Time:</label>
                    <select name="new_time" required>
                        <option value="10:00-11:00">10:00-11:00</option>
                        <option value="11:00-12:00">11:00-12:00</option>
                        <option value="12:00-1:00">12:00-1:00</option>
                        <option value="1:00-2:00">1:00-2:00</option>
                        <option value="2:00-3:00">2:00-3:00</option>
                        <option value="3:00-4:00">3:00-4:00</option>
                    </select>
                </div>
                <div class="button-container">
                    <input type="submit" value="Reschedule" class="btn-reschedule">
                    <a href="appointment.php" class="btn-reschedule btn-cancel">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
