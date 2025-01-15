<?php
session_start();
include("../connection.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../phpmailer/src/Exception.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';

if (!isset($_SESSION["user"]) || $_SESSION['usertype'] != 'a') {
    header("location: ../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $appointment_id = $_GET['id'];
    
    // Get appointment details including patient email
    $query = "SELECT b.*, p.pemail, p.pname, d.docname 
              FROM booking b 
              JOIN patient p ON b.pid = p.pid 
              JOIN doctor d ON d.docid = b.did 
              WHERE b.id = ?";
              
    $stmt = $database->prepare($query);
    $stmt->bind_param("i", $appointment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
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
            $mail->addAddress($row['pemail']);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Appointment Reminder - SmileSlot Dental Clinic';
            
            $mail->Body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                    <div style='text-align: center; padding: 20px;'>
                        <h2 style='color: #00a884;'>Appointment Reminder</h2>
                    </div>
                    
                    <div style='padding: 20px; background-color: #f9f9f9; border-radius: 5px;'>
                        <p>Dear {$row['pname']},</p>
                        
                        <p>This is a reminder for your upcoming dental appointment:</p>
                        
                        <p><strong>Appointment Details:</strong></p>
                        <ul>
                            <li>Date: {$row['DATE']}</li>
                            <li>Time: {$row['time_slot']}</li>
                            <li>Dentist: Dr. {$row['docname']}</li>
                            <li>Description: {$row['dscrptn']}</li>
                        </ul>
                        
                        <p><strong>Important Notes:</strong></p>
                        <ul>
                            <li>Please arrive 15 minutes before your scheduled appointment</li>
                            <li>If you need to reschedule, please contact us at least 24 hours in advance</li>
                        </ul>
                    </div>
                    
                    <div style='text-align: center; padding: 20px; color: #666;'>
                        <p>Thank you for choosing SmileSlot Dental Clinic!</p>
                        <p style='font-size: 12px;'>This is an automated message, please do not reply to this email.</p>
                    </div>
                </div>";

            $mail->send();
            echo "<script>
                    alert('Reminder email sent successfully!');
                    window.location.href = 'appointment.php';
                  </script>";
        } catch (Exception $e) {
            echo "<script>
                    alert('Failed to send reminder email: {$mail->ErrorInfo}');
                    window.location.href = 'appointment.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Appointment not found.');
                window.location.href = 'appointment.php';
              </script>";
    }
    
    $stmt->close();
} else {
    header("location: appointment.php");
}
?> 