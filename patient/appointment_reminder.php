<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../phpmailer/src/Exception.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';
include("../connection.php");

function sendReminderEmail($patientEmail, $patientName, $appointmentDate, $timeSlot, $doctorName, $isUrgent = false) {
    $mail = new PHPMailer(true);
    
    try {
        // Add debug logging
        error_log("Attempting to send reminder email to: " . $patientEmail);
        
        // Server settings
        $mail->SMTPDebug = 2; // Enable verbose debug output
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'rimasdentalclinic16@gmail.com'; // Replace with your email
        $mail->Password = 'qimqrkgqywrkypmy'; // Replace with your app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('rimasdentalclinic16@gmail.com', 'SmileSlot Dental Clinic');
        $mail->addAddress($patientEmail, $patientName);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $isUrgent ? 'Urgent Reminder: Upcoming Dental Appointment in 3 Hours' : 'Reminder: Upcoming Dental Appointment Tomorrow';
        
        $timeMessage = $isUrgent ? "in 3 hours" : "tomorrow";
        
        $mail->Body = "
            <html>
            <body>
                <h2>Appointment Reminder</h2>
                <p>Dear $patientName,</p>
                <p>This is a friendly reminder that you have an upcoming dental appointment $timeMessage:</p>
                <ul>
                    <li><strong>Date:</strong> $appointmentDate</li>
                    <li><strong>Time:</strong> $timeSlot</li>
                    <li><strong>Doctor:</strong> $doctorName</li>
                </ul>
                <p>Please arrive 15 minutes before your scheduled appointment.</p>
                <p>If you need to reschedule, please contact us as soon as possible.</p>
                <br>
                <p>Best regards,<br>SmileSlot Dental Clinic</p>
            </body>
            </html>
        ";

        $mail->send();
        error_log("Successfully sent reminder email to: " . $patientEmail);
        return true;
    } catch (Exception $e) {
        error_log("Failed to send reminder email to: " . $patientEmail);
        error_log("Error message: " . $mail->ErrorInfo);
        return false;
    }
}

// Check for upcoming appointments and send reminders
function checkAndSendReminders() {
    global $database;
    
    error_log("Starting reminder check at: " . date('Y-m-d H:i:s'));
    
    // Get appointments for tomorrow
    $tomorrow = date('Y-m-d', strtotime('+1 day'));
    error_log("Checking appointments for: " . $tomorrow);
    
    // Get appointments for tomorrow that haven't had reminders sent
    $query = "SELECT b.* 
              FROM booking b 
              WHERE b.DATE = ? 
              AND b.reminder_sent = 0 
              AND LOWER(b.status) = 'confirmed'";
              
    $stmt = $database->prepare($query);
    $stmt->bind_param('s', $tomorrow);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        error_log("Processing reminder for appointment ID: " . $row['ID']);
        
        // Send reminder email
        $reminderSent = sendReminderEmail(
            $row['pemail'],
            $row['PATIENTNAME'], 
            $row['DATE'],
            $row['time_slot'],
            $row['dname'],
            false
        );

        if ($reminderSent) {
            // Update the reminder_sent status
            $updateStmt = $database->prepare("UPDATE booking SET reminder_sent = 1 WHERE ID = ?");
            $updateStmt->bind_param('i', $row['ID']);
            $updateStmt->execute();
            $updateStmt->close();
            error_log("Reminder sent and status updated for appointment ID: " . $row['ID']);
        } else {
            error_log("Failed to send reminder for appointment ID: " . $row['ID']);
        }
    }
    $stmt->close();

    // Also check for appointments in next 3 hours
    $threeHoursLater = date('Y-m-d H:i:s', strtotime('+3 hours'));
    $currentDate = date('Y-m-d');
    
    error_log("Checking urgent reminders for appointments before: " . $threeHoursLater);
    
    $query = "SELECT b.*, p.pemail 
              FROM booking b 
              JOIN patient p ON b.pid = p.pid 
              WHERE b.DATE = ? 
              AND b.urgent_reminder_sent = 0 
              AND b.status = 'confirmed'
              AND CONCAT(b.DATE, ' ', b.time_slot) <= ?";
              
    $stmt = $database->prepare($query);
    $stmt->bind_param('ss', $currentDate, $threeHoursLater);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        error_log("Processing urgent reminder for appointment ID: " . $row['ID']);
        
        // Send urgent reminder
        $reminderSent = sendReminderEmail(
            $row['pemail'],
            $row['PATIENTNAME'],
            $row['DATE'], 
            $row['time_slot'],
            $row['dname'],
            true
        );

        if ($reminderSent) {
            // Update the urgent_reminder_sent status
            $updateStmt = $database->prepare("UPDATE booking SET urgent_reminder_sent = 1 WHERE ID = ?");
            $updateStmt->bind_param('i', $row['ID']);
            $updateStmt->execute();
            $updateStmt->close();
            error_log("Urgent reminder sent and status updated for appointment ID: " . $row['ID']);
        } else {
            error_log("Failed to send urgent reminder for appointment ID: " . $row['ID']);
        }
    }
    $stmt->close();
}

// Run the reminder check
checkAndSendReminders(); 