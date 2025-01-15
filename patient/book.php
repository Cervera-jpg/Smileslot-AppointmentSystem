<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../phpmailer/src/Exception.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';

session_start();

if (!isset($_SESSION["user"]) || $_SESSION["user"] == "" || $_SESSION["usertype"] != "p") {
    header("location: ../login.php");
    exit();
}

$useremail = $_SESSION["user"];

// Connect to database and get patient email
$mysqli = new mysqli('localhost', 'root', '', 'smileslot');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get patient details
$stmt = $mysqli->prepare("SELECT pemail, pname, pid, ptel FROM patient WHERE pemail = ?");
$stmt->bind_param('s', $useremail);
$stmt->execute();
$result = $stmt->get_result();
$patient = $result->fetch_assoc();

if(isset($_POST['booknow'])){
    // Get form data
    $time_slot = $_POST['time_slot'];
    $date = $_GET['date'];
    $description = $_POST['description'];
    
    // Get doctor details
    $did = 1;
    $dname = "Dra Marrisa Morada";
    if(isset($_POST['appointment_type']) && $_POST['appointment_type'] == "dentisttwo") {
        $did = 2;
        $dname = "Dra. Athenna Denise Morada";
    }

    // Debug output
    echo "<pre>";
    echo "Inserting booking with data:\n";
    print_r([
        'PATIENTNAME' => $patient['pname'],
        'PHONE' => $patient['ptel'],
        'EMAIL' => $patient['pemail'],
        'DATE' => $date,
        'time_slot' => $time_slot,
        'dscrptn' => $description,
        'status' => 'pending',
        'pid' => $patient['pid'],
        'did' => $did,
        'dname' => $dname
    ]);
    echo "</pre>";

    // Prepare INSERT statement
    $sql = "INSERT INTO booking (PATIENTNAME, PHONE, EMAIL, DATE, time_slot, dscrptn, status, pid, did, dname) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    
    if (!$stmt) {
        die("Error preparing statement: " . $mysqli->error);
    }
    
    $status = 'pending';
    
    $stmt->bind_param('sssssssiis', 
        $patient['pname'],     // Patient name
        $patient['ptel'],      // Phone
        $patient['pemail'],    // Email
        $date,                 // Date
        $time_slot,           // Time slot
        $description,         // Description
        $status,             // Status
        $patient['pid'],      // Patient ID
        $did,                 // Doctor ID
        $dname               // Doctor name
    );
    
    // Add error checking for the execution
    if(!$stmt->execute()){
        die("Error executing statement: " . $stmt->error);
    }
    
    // If successful, proceed with email and redirect
    if($stmt->affected_rows > 0){
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
            $mail->setFrom('jchrstn0401@gmail.com', 'RIMAS DENTAL CLINIC');
            $mail->addAddress($patient['pemail']);

            // Email content
            $mail->isHTML(true);
            $mail->Subject = "Your Appointment on " . $date;
            $mail->Body = "Dear {$patient['pname']}, your appointment has been booked successfully for $date at $time_slot. Your dentist is $dname. Please be at the clinic 15 minutes before your appointment time.";

            $mail->send();
            
            // Redirect to appointment page or show success message
            header("Location: appointment.php?booking=success");
            exit();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Error: Booking was not saved. " . $mysqli->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/main.css">
    <style>
        .container {
            margin-top: 50px;
        }
        .alert {
            font-size: 18px;
            padding: 20px;
        }
        .alert strong {
            font-weight: bold;
        }
        h1.alert {
            font-size: 32px;
        }
        .form-group label {
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="alert alert-danger text-center" style="background:#E4B1F0; border:none; color:#fff">Booking Appointment</h1>
        <div class="row">
            <div class="col-md-12">
                <!-- Display success or error message -->
                <?php echo isset($message) ? $message : ''; ?>
                <!-- Booking form -->
                <form action="" method="post" autocomplete="off">
                    <div class="form-group">
                        <label for="time_slot">Time Slot</label>
                        <select name="time_slot" class="form-control" required>
                            <option value="10:00-11:00">10:00-11:00</option>
                            <option value="11:00-12:00">11:00-12:00</option>
                            <option value="01:00-02:00">01:00-02:00</option>
                            <option value="02:00-03:00">02:00-03:00</option>
                            <option value="03:00-04:00">03:00-04:00</option>
                        </select>
                    </div>
                    
                    <!-- New appointment type dropdown -->
                    <div class="form-group">
                        <label for="appointment_type">Select your Dentist</label>
                        <select name="appointment_type" class="form-control" required>
                            <option value="dentistone">Dra. Marrisa Morada</option>
                            <option value="dentisttwo">Dra. Athenna Denise Morada</option>
                        </select>
                    </div>

                    <!-- New description field -->
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Please describe your dental concern"></textarea>
                    </div>

                    <button class="btn btn-primary" type="submit" name="booknow">Submit</button>
                    <a href="index.php" class="btn btn-default">Back</a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>
