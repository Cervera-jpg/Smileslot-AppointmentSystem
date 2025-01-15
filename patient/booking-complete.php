<?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require '../phpmailer/src/Exception.php';
    require '../phpmailer/src/PHPMailer.php';
    require '../phpmailer/src/SMTP.php';
    // Learn from w3schools.com
    session_start();

    if (!isset($_SESSION['booking_data'])) {
        header("location: book.php");
        exit();
    }

    $booking_data = $_SESSION['booking_data'];
    $useremail = $booking_data['useremail'];  // This will now be the pemail from patient table
    $userid = $booking_data['userid'];
    $username = $booking_data['username'];
    $date = $booking_data['date'];
    $time_slot = $booking_data['time_slot'];

    // Import database
    include("../connection.php");
    $userrow = $database->query("SELECT * FROM patient WHERE pemail='$useremail'");
    $userfetch = $userrow->fetch_assoc();
    $userid = $userfetch["pid"];
    $username = $userfetch["pname"];

    if ($_POST) {
        if (isset($_POST["booknow"])) {
            $apponum = $_POST["apponum"];
            $scheduleid = $_POST["scheduleid"];  // Corrected redundant assignment
            $date = $_POST["date"];

            // Insert appointment into the database
            $sql2 = "INSERT INTO appointment(pid, apponum, scheduleid, appodate) VALUES ($userid, $apponum, $scheduleid, '$date')";
            $result = $database->query($sql2);

            if ($result) {
                // Send an email notification using PHPMailer
                $mail = new PHPMailer(true);

                try {
                    // SMTP settings
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'jchrstn0401@gmail.com';  // Fixed to `Username`
                    $mail->Password = 'pifbfykmfbivpzil';  // Correct password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    $mail->Port = 465;  // Fixed to `Port`

                    // Sender and recipient
                    $mail->setFrom('jchrstn0401@gmail.com', 'RIMAS DENTAL CLINIC');  // Include sender's name
                    $mail->addAddress($useremail);  // This will use the pemail from patient table

                    // Email content
                    $mail->isHTML(true);
                    $mail->Subject = "Your Appointment on " . $date;  // Better subject formatting
                    $mail->Body = "Dear $username, your appointment has been booked successfully. Appointment Number: $apponum, Schedule ID: $scheduleid, Date: $date.";

                    // Send email
                    $mail->send();
                    echo "Email has been sent successfully!";
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }

                // Redirect after email is sent
                header("Location: appointment.php?action=booking-added&id=" . $apponum . "&titleget=none");
                exit();  // Always exit after redirect
            } else {
                echo "Error: " . $database->error;
            }
        }
    }
?>
