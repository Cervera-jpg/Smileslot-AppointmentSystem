<?php 
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../phpmailer/src/Exception.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';

include("../connection.php");

if (isset($_SESSION["user"])) {
    if (($_SESSION["user"]) == "" || $_SESSION['usertype'] != 'p') {
        header("location: ../login.php");
        exit();
    } else {
        $useremail = $_SESSION["user"];
    }
} else {
    header("location: ../login.php");
    exit();
}

// Create database connections
$mysqli = new mysqli('localhost', 'root', '', 'smileslot');
include("../connection.php");
function build_calendar($month, $year) {
    $mysqli = new mysqli('localhost', 'root', '', 'smileslot');
    $stmt = $mysqli->prepare("SELECT ID, PATIENTNAME, PHONE, EMAIL, DATE, time_slot, dscrptn, did, dname, status 
                            FROM booking 
                            WHERE MONTH(DATE) = ? AND YEAR(DATE) = ?");
    $stmt->bind_param('ss', $month, $year);
    $bookings = array();
    
    // Fetch existing bookings
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Store all booking details in array including status
                $bookings[$row['DATE']][] = [
                    'time_slot' => $row['time_slot'],
                    'patient' => $row['PATIENTNAME'],
                    'phone' => $row['PHONE'],
                    'email' => $row['EMAIL'],
                    'description' => $row['dscrptn'],
                    'doctor' => $row['dname'],
                    'did' => $row['did'],
                    'status' => $row['status']
                ];
            }
        }
        $stmt->close();
    }

    $daysOfWeek = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
    $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
    $numberDays = date('t', $firstDayOfMonth);
    $dateComponents = getdate($firstDayOfMonth);
    $monthName = $dateComponents['month'];
    $dayOfWeek = $dateComponents['wday'];

    $datetoday = date('Y-m-d');

    $calendar = "<table class='custom-calendar'>";
    $calendar .= "<div class='calendar-month-controls'>
                    <h2>$monthName $year</h2>
                    <div class='calendar-nav-buttons'>
                        <a class='calendar-button' href='?month=" . date('m', mktime(0, 0, 0, $month - 1, 1, $year)) . "&year=" . date('Y', mktime(0, 0, 0, $month - 1, 1, $year)) . "'>Previous Month</a>
                        <a class='calendar-button' href='?month=" . date('m') . "&year=" . date('Y') . "'>Current Month</a>
                        <a class='calendar-button' href='?month=" . date('m', mktime(0, 0, 0, $month + 1, 1, $year)) . "&year=" . date('Y', mktime(0, 0, 0, $month + 1, 1, $year)) . "'>Next Month</a>
                    </div>
                  </div>";

    $calendar .= "<tr>";
    foreach ($daysOfWeek as $day) {
        $calendar .= "<th class='header'>$day</th>";
    } 

    $currentDay = 1;
    $calendar .= "</tr><tr>";

    if ($dayOfWeek > 0) { 
        for ($k = 0; $dayOfWeek > $k; $k++) {
            $calendar .= "<td class='calendar-empty'></td>"; 
        }
    }

    $month = str_pad($month, 2, "0", STR_PAD_LEFT);

    while ($currentDay <= $numberDays) {
        if ($dayOfWeek == 7) {
            $dayOfWeek = 0;
            $calendar .= "</tr><tr>";
        }

        $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
        $date = "$year-$month-$currentDayRel";
        $today = $date == date('Y-m-d') ? "calendar-today" : "";

        // Get the booked time slots for this date
        $timeSlots = isset($bookings[$date]) ? $bookings[$date] : [];

       if (date('l', strtotime($date)) == 'Sunday') {
    // If the day is Sunday, mark it as closed
    $calendar .= "<td class='calendar-closed'><h4>$currentDay</h4><p>Closed</p></td>";
} elseif ($date < date('Y-m-d')) {
    $calendar .= "<td><h4>$currentDay</h4><button class='calendar-btn calendar-btn-danger' disabled>N/A</button>";
} else {
    $calendar .= "<td class='$today'><h4>$currentDay</h4>";
    // Define available time slots
    $availableSlots = [
        '10:00-11:00',
        '11:00-12:00',
        '12:00-1:00',
        '1:00-2:00',
        '2:00-3:00',
        '3:00-4:00'
    ];

    foreach ($availableSlots as $slot) {
        $isBooked = false;
        $bookingInfo = null;
        $isPastTimeSlot = false;

        // Check if the slot is in the past for current day
        if ($date == date('Y-m-d')) {
            $slotStart = explode('-', $slot)[0];
            // Convert to 24-hour format for comparison
            if (strpos($slotStart, ':') === false) {
                $slotStart .= ':00';
            }
            $slotDateTime = DateTime::createFromFormat('H:i', $slotStart);
            if ($slotDateTime) {
                $currentTime = new DateTime();
                if ($slotDateTime < $currentTime) {
                    $isPastTimeSlot = true;
                }
            }
        }

        // Rest of your booking check logic...
        if (isset($bookings[$date])) {
            foreach ($bookings[$date] as $booking) {
                // Get the stored time slot
                $bookingTime = $booking['time_slot'];
                
                // Convert single-digit hours to double-digit format
                $bookingParts = explode('-', $bookingTime);
                if (count($bookingParts) == 2) {
                    $startTime = explode(':', $bookingParts[0]);
                    $endTime = explode(':', $bookingParts[1]);
                    
                    // Add leading zero if hour is single digit
                    if (strlen($startTime[0]) == 1) {
                        $startTime[0] = '0' . $startTime[0];
                    }
                    if (strlen($endTime[0]) == 1) {
                        $endTime[0] = '0' . $endTime[0];
                    }
                    
                    // Reconstruct the time string in consistent format
                    $bookingTime = $startTime[0] . ':00-' . $endTime[0] . ':00';
                }
                
                // Convert slot format to match database format
                $slotParts = explode('-', $slot);
                if (count($slotParts) == 2) {
                    $startTime = explode(':', $slotParts[0]);
                    $endTime = explode(':', $slotParts[1]);
                    
                    // Add leading zero if hour is single digit
                    if (strlen($startTime[0]) == 1) {
                        $startTime[0] = '0' . $startTime[0];
                    }
                    if (strlen($endTime[0]) == 1) {
                        $endTime[0] = '0' . $endTime[0];
                    }
                    
                    $normalizedSlot = $startTime[0] . ':00-' . $endTime[0] . ':00';
                    
                    if ($bookingTime == $normalizedSlot) {
                        $isBooked = true;
                        $bookingInfo = $booking;
                        break;
                    }
                }
            }
        }

        if ($isBooked) {
            $status = $bookingInfo['status'] ? " (Status: {$bookingInfo['status']})" : "";
            $calendar .= "<span class='time-slot booked' 
                           title='Booked by: {$bookingInfo['patient']}{$status}'>
                             $slot
                         </span>";
        } elseif ($isPastTimeSlot) {
            $calendar .= "<span class='time-slot booked' 
                           title='This time slot has passed'>
                             $slot
                         </span>";
        } else {
            $calendar .= "<a href='javascript:void(0)' 
                             onclick='openBookingModal(\"$date\", \"$slot\")' 
                             class='time-slot available'>
                              $slot
                         </a>";
        }
    }
    $calendar .= "</td>";
}

        $calendar .= "</td>";
        $currentDay++;
        $dayOfWeek++;
    }

    if ($dayOfWeek != 7) { 
        $remainingDays = 7 - $dayOfWeek;
        for ($l = 0; $l < $remainingDays; $l++) {
            $calendar .= "<td class='calendar-empty'></td>"; 
        }
    }

    $calendar .= "</tr>";
    $calendar .= "</table>";
    echo $calendar;
}
?>

<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sessions</title>

    <!-- CSS Styles -->
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/finalmain.css?v=<?php echo time();?>"/>    
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/navbar.css">

    <!-- FullCalendar Styles and Script -->
    
    <style>
        .popup { animation: transitionIn-Y-bottom 0.5s; }
        .sub-table { animation: transitionIn-Y-bottom 0.5s; }
        #calendar {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }
        .fc .fc-toolbar-title {
            font-size: 24px;
            font-weight: bold;
        }
        .fc-daygrid-event {
            font-size: 14px;
        }
        .logout-btn:hover, .btn-book:hover, .btn-icon-back:hover {
            background-color: #912bbc;
        }
        .time-slot {
            display: block;
            margin: 2px 0;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            text-decoration: none;
            white-space: nowrap;
            text-align: center;
        }
        .time-slot.booked {
            background-color: #ff6b6b;
            color: white;
            cursor: not-allowed;
        }
        .time-slot.available {
            background-color: #51cf66;
            color: white;
        }
        .time-slot.available:hover {
            background-color: #40c057;
        }
        .custom-calendar td {
            padding: 8px;
            height: auto;
            min-width: 150px;
        }
        .date-number {
            margin-bottom: 8px;
            font-weight: bold;
            text-align: center;
        }
        .slots-container {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        /* Calendar Container Styles */
        .calendar-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            border-radius: 12px;
        }

        .custom-calendar {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background-color: #ffffff;
            border: none;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            border-radius: 12px;
            overflow: hidden;
        }

        /* Header Styles */
        .custom-calendar th {
            background-color: #912bbc;
            color: white;
            padding: 15px;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* Cell Styles */
        .custom-calendar td {
            text-align: center;
            vertical-align: top;
            padding: 12px;
            border: 1px solid #f0f0f0;
            height: 120px;
            transition: all 0.3s ease;
        }

        .custom-calendar td:hover {
            background-color: #f8f4fb;
        }

        /* Time Slot Styles */
        .time-slot {
            display: block;
            margin: 3px 0;
            padding: 6px 8px;
            border-radius: 6px;
            font-size: 12px;
            text-decoration: none;
            white-space: nowrap;
            text-align: center;
            transition: all 0.3s ease;
        }

        .time-slot.booked {
            background-color: #ff8080;
            color: white;
            cursor: not-allowed;
        }

        .time-slot.available {
            background-color: #4CAF50;
            color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .time-slot.available:hover {
            background-color: #45a049;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        /* Calendar Navigation */
        .calendar-month-controls {
            margin-bottom: 30px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            padding: 0 20px;
        }

        .calendar-month-controls h2 {
            font-size: 28px;
            color: #912bbc;
            margin: 0;
            font-weight: 600;
        }

        .calendar-nav-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
            width: 100%;
        }

        .calendar-button {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            background-color: #912bbc;
            color: white;
            border-radius: 25px;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 2px 4px rgba(145, 43, 188, 0.2);
        }

        .calendar-button:hover {
            background-color: #7b1b9e;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(145, 43, 188, 0.3);
        }

        /* Today's Date Highlight */
        .calendar-today {
            background-color: #F5F5DC;  /* Light purple background */
            position: relative;
            border: 2px solid #7F00FF !important;  /* Purple border */
        }

        .calendar-today h4 {
            color: #912bbc;  /* Purple text for the date number */
            font-weight: bold;
        }

        /* Optional: Add a "Today" label */
        .calendar-today::after {
            content: 'Today';
            position: absolute;
            top: 2px;
            right: 2px;
            font-size: 10px;
            padding: 2px 6px;
            background-color: #912bbc;
            color: white;
            border-radius: 3px;
            font-weight: 500;
        }

        /* Empty Cells */
        .calendar-empty {
            background-color: #f9f9f9;
        }

        /* Day Numbers */
        .custom-calendar td h4 {
            margin: 0 0 8px 0;
            font-size: 16px;
            color: #333;
        }

        /* Closed Day Style */
        .calendar-closed {
            background-color: #f5f5f5;
        }

        .calendar-closed p {
            color: #fff;
            margin: 5px 0;
            font-style: italic;
        }

        .time-slot.past {
            background-color: #999999;
            color: white;
            cursor: not-allowed;
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
    </nav>
</body>
<body>
<?php
// Start session and check user authentication


// Include database connection
include("../connection.php");

// Fetch user details
$userrow = $database->query("select * from patient where pemail='$useremail'");
$userfetch = $userrow->fetch_assoc();
$userid = $userfetch["pid"];
$username = $userfetch["pname"];
$profilePicPath = $userfetch['profile_pic'] ?? 'default-profile.png';
    $fullPath = "../uploads/$profilePicPath";

    // Fallback to default image if the file does not exist
    if (!file_exists($fullPath)) {
        $fullPath = "../img/patient.png";
    }

// Set timezone and fetch today's date
date_default_timezone_set('Asia/manila');
$today = date('Y-m-d');
?>

<!-- Dashboard Menu -->
<div class="container">
    <div class="menu">
        <table class="menu-container" border="0">
            <tr>
                <td colspan="2" style="padding:10px">
                    <table class="profile-container" border="0">
                       <tr>
                           <td width="30%" style="padding-left:20px">
                                    <form id="profilePicForm" action="upload_profile_pic.php" method="POST" enctype="multipart/form-data">
                                        <div class="profile-pic-container">
                                            <label for="profilePicInput">
                                               <img id="profilePic" src="<?php echo htmlspecialchars($profilePicPath, ENT_QUOTES, 'UTF-8'); ?>" 
         alt="Profile Picture" class="profile-pic">
                                                <div class="profile-overlay">
                                                    <p>Change Picture</p>
                                                </div>
                                            </label>
                                            <input 
                                                type="file" 
                                                id="profilePicInput" 
                                                name="profile_pic" 
                                                style="display: none;" 
                                                accept="image/*" 
                                                onchange="submitForm(event)">
                                        </div>
                                    </form>
                            </td>
                            <td>
                                <p class="profile-title">
                                    <?php echo substr($username, 0, 30) ?>
                                </p>
                                <p class="profile-subtitle">
                                    <?php echo substr($useremail, 0, 30) ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <a href="../logout.php">
                                    <input type="button" value="Log out" 
                                           class="logout-btn btn-primary-soft btn">
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="menu-row">
                <td class="menu-btn menu-icon-home">
                    <a href="index.php" class="non-style-link-menu">
                        <div><p class="menu-text">Home</p></div></a>
                </td>
            </tr>
            <tr class="menu-row">
                <td class="menu-btn menu-icon-doctor">
                    <a href="doctors.php" class="non-style-link-menu">
                        <div><p class="menu-text">Dentist</p></div></a>
                </td>
            </tr>
            <tr class="menu-row">
                <td class="menu-btn menu-icon-schedule menu-active menu-icon-schedule-active">
                    <a href="schedule.php" class="non-style-link-menu 
                       non-style-link-menu-active">
                        <div><p class="menu-text">Scheduled Sessions</p></div>
                    </a>
                </td>
            </tr>
            <tr class="menu-row">
                <td class="menu-btn menu-icon-appoinment">
                    <a href="appointment.php" class="non-style-link-menu">
                        <div><p class="menu-text">My Appointments</p></div></a>
                </td>
            </tr>
            <tr class="menu-row">
                <td class="menu-btn menu-icon-settings">
                    <a href="settings.php" class="non-style-link-menu">
                        <div><p class="menu-text">Settings</p></div></a>
                </td>
            </tr>
            <tr class="menu-row" >
                    <td class="menu-btn menu-icon-request">
                        <a href="requesttransfer.php" class="non-style-link-menu"><div><p class="menu-text">Request Transfer</p></a></div>
                    </td>
                </tr>
            <tr class="menu-row" >
                    <td class="menu-btn menu-icon-payment">
                        <a href="payment.php" class="non-style-link-menu"><div><p class="menu-text">Payment</p></a></div>
                    </td>
                </tr>
        </table>
    </div>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking System</title>
    <style>
       h1 {
           font-family: Arial, sans-serif;
           background-color: #fff;
           margin: 0;
           padding: 0;
           text-align: center;
       }

       .calendar-container {
           max-width: 1200px;
           margin: 0 auto;
           padding: 20px;
       }

       .custom-calendar {
           width: 100%;
           border-collapse: collapse;
           background-color: #e9d1f0;
           border: 1px solid #bba4d4;
       }

       .custom-calendar th {
           background-color: #d28ff2;
           color: white;
           padding: 10px;
           font-size: 16px;
       }

       .custom-calendar td {
           text-align: center;
           vertical-align: top;
           padding: 10px;
           border: 1px solid #bba4d4;
       }

       .calendar-empty {
           background-color: #f4e7ff;
       }

       h4 {
           margin: 5px 0;
       }

       .calendar-btn {
           padding: 5px 10px;
           border: none;
           border-radius: 5px;
           cursor: pointer;
       }

       .calendar-btn-success {
           background-color: #5cb85c;
           color: white;
       }

       .calendar-btn-danger {
           background-color: #d9534f;
           color: white;
       }

       .calendar-month-controls {
           margin-bottom: 20px;
           text-align: center;
       }

       .calendar-button {
           display: inline-block;
           padding: 8px 15px;
           margin: 5px;
           background-color: #337ab7;
           color: white;
           border-radius: 4px;
           text-decoration: none;
           font-size: 14px;
       }

       .calendar-button:hover {
           background-color: #286090;
       }

      
       .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 8px;
            position: relative;
        }

        .close {
            position: absolute;
            right: 15px;
            top: 10px;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: #912bbc;
        }

        .booking-form {
            padding: 20px;
        }

        .booking-form .form-group {
            margin-bottom: 15px;
        }

        .booking-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .booking-form select,
        .booking-form textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .booking-form button {
            background-color: #d28ff2;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .booking-form button:hover {
            background-color: #912bbc;
        }
   </style>
</head>

<body>
    
    <div class="calendar-container">
        <div class="alert alert-danger" style="background:#E4B1F0;border:none;color:#fff">
            <h1>Booking System</h1>     
            <?php 
                $dateComponents = getdate();
                if (isset($_GET['month']) && isset($_GET['year'])) {
                    $month = (int)$_GET['month'];
                    $year = (int)$_GET['year'];
                } else {
                    $month = $dateComponents['mon'];
                    $year = $dateComponents['year'];
                }
                echo build_calendar($month, $year);
            ?>
        </div>
    </div>
    <script src="../script.js"></script>
</body>
</html>


<!-- Booking Modal -->
<div id="bookingModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 style="text-align: center; color: #912bbc;">Book Appointment</h2>
        <form class="booking-form" action="" method="post" autocomplete="off">
            <input type="hidden" id="selectedDate" name="date">
            <input type="hidden" id="selectedTimeSlot" name="time_slot">
            
            <div class="form-group">
                <label for="appointment_type">Select your Dentist</label>
                <select name="appointment_type" class="form-control" required>
                    <option value="dentistone">Dra. Marrisa Morada</option>
                    <option value="dentisttwo">Dra. Athenna Denise Morada</option>
                </select>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" class="form-control" rows="3" 
                    placeholder="Please describe your dental concern" required></textarea>
            </div>

            <div style="text-align: center;">
                <button type="submit" name="booknow">Book Now</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Modal functionality
    const modal = document.getElementById("bookingModal");
    const span = document.getElementsByClassName("close")[0];

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // Function to open modal and set appointment details
    function openBookingModal(date, timeSlot) {
        document.getElementById('selectedDate').value = date;
        document.getElementById('selectedTimeSlot').value = timeSlot;
        modal.style.display = "block";
    }
</script>

<?php
// Add this after session_start() and before the HTML

if(isset($_POST['booknow'])){
    $time_slot = $_POST['time_slot'];
    $date = $_POST['date'];
    $description = $_POST['description'];
    
    // Get doctor details
    $did = 1;
    $dname = "Dra Marrisa Morada";
    if(isset($_POST['appointment_type']) && $_POST['appointment_type'] == "dentisttwo") {
        $did = 2;
        $dname = "Dra. Athenna Denise Morada";
    }

    // Insert booking
    $stmt = $mysqli->prepare("INSERT INTO booking (PATIENTNAME, PHONE, EMAIL, DATE, time_slot, dscrptn, status, pid, did, dname) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $status = 'pending';
    
    $stmt->bind_param('sssssssiis', 
        $userfetch["pname"],
        $userfetch["ptel"],
        $useremail,
        $date,
        $time_slot,
        $description,
        $status,
        $userid,
        $did,
        $dname
    );
    
    if($stmt->execute()){
        // Send email notification
        $mail = new PHPMailer(true);

        try {
            // SMTP settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'rimasdentalclinic16@gmail.com';
            $mail->Password = 'qimqrkgqywrkypmy';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            // Sender and recipient
            $mail->setFrom('rimasdentalclinic16@gmail.com', 'RIMAS DENTAL CLINIC');
            $mail->addAddress($useremail);

            // Email content
            $mail->isHTML(true);
            $mail->Subject = "Appointment Confirmation - RIMAS DENTAL CLINIC";
            
            // Create HTML email body
            $emailBody = "
                <h2>Appointment Confirmation</h2>
                <p>Dear {$userfetch["pname"]},</p>
                <p>Your appointment has been successfully booked with the following details:</p>
                <ul>
                    <li><strong>Date:</strong> $date</li>
                    <li><strong>Time:</strong> $time_slot</li>
                    <li><strong>Dentist:</strong> $dname</li>
                    <li><strong>Description:</strong> $description</li>
                </ul>
                <p>Please arrive 15 minutes before your scheduled appointment time.</p>
                <p>If you need to reschedule or cancel your appointment, please contact us as soon as possible.</p>
                <br>
                <p>Best regards,<br>RIMAS DENTAL CLINIC Team</p>
            ";
            
            $mail->Body = $emailBody;
            $mail->AltBody = strip_tags($emailBody); // Plain text version

            $mail->send();
            echo "<script>
                    alert('Booking successful! A confirmation email has been sent to your email address.');
                    window.location.href='appointment.php';
                  </script>";
        } catch (Exception $e) {
            echo "<script>
                    alert('Booking successful but failed to send email confirmation. Error: {$mail->ErrorInfo}');
                    window.location.href='appointment.php';
                  </script>";
        }
    } else {
        echo "<script>alert('Booking failed. Please try again.');</script>";
    }
}