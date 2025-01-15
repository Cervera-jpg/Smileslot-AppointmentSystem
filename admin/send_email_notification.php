<?php
session_start();
include("../connection.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../phpmailer/src/Exception.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';

if(isset($_GET['id']) && isset($_GET['type'])) {
    $id = $_GET['id'];
    $type = $_GET['type'];

    // Get request details
    $query = "SELECT * FROM `request-table` WHERE id = ?";
    $stmt = $database->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $request = $result->fetch_assoc();

    if($request) {
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
            $mail->addAddress($request['pemail']);

            // Content
            $mail->isHTML(true);
            
            if($type === 'approve') {
                $mail->Subject = 'Request Transfer Approved - SmileSlot Dental Clinic';
                $mail->Body = "
                    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                        <div style='text-align: center; padding: 20px;'>
                            <h2 style='color: #00a884;'>Your Request Transfer has been Approved</h2>
                        </div>
                        
                        <div style='padding: 20px; background-color: #f9f9f9; border-radius: 5px;'>
                            <p>Dear {$request['pname']},</p>
                            
                            <p>We are pleased to inform you that your request transfer to <strong>{$request['referralto']}</strong> has been approved.</p>
                            
                            <p><strong>Request Details:</strong></p>
                            <ul>
                                <li>Request Date: {$request['request_date']}</li>
                                <li>Referring Dentist: {$request['dname']}</li>
                                <li>Referral To: {$request['referralto']}</li>
                            </ul>
                            
                            <p><strong>Next Steps:</strong></p>
                            <ol>
                                <li>Please proceed with the payment through our system</li>
                                <li>Once payment is confirmed, you can download or print your referral form</li>
                                <li>Present the referral form to {$request['referralto']}</li>
                            </ol>
                        </div>
                        
                        <div style='text-align: center; padding: 20px; color: #666;'>
                            <p>Thank you for choosing SmileSlot Dental Clinic!</p>
                            <p style='font-size: 12px;'>This is an automated message, please do not reply to this email.</p>
                        </div>
                    </div>
                ";
            } else if($type === 'reject') {
                $mail->Subject = 'Request Transfer Status Update - SmileSlot Dental Clinic';
                $mail->Body = "
                    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                        <div style='text-align: center; padding: 20px;'>
                            <h2 style='color: #ff4646;'>Request Transfer Status Update</h2>
                        </div>
                        
                        <div style='padding: 20px; background-color: #f9f9f9; border-radius: 5px;'>
                            <p>Dear {$request['pname']},</p>
                            
                            <p>We regret to inform you that your request transfer to <strong>{$request['referralto']}</strong> has been reviewed and could not be approved at this time.</p>
                            
                            <p><strong>Request Details:</strong></p>
                            <ul>
                                <li>Request Date: {$request['request_date']}</li>
                                <li>Referring Dentist: {$request['dname']}</li>
                                <li>Referral To: {$request['referralto']}</li>
                            </ul>
                            
                            <p>If you would like to discuss this decision or explore alternative options, please contact our office directly.</p>
                            
                            <p>We understand this may not be the outcome you were hoping for, and we're here to help you explore other treatment options that might better suit your needs.</p>
                        </div>
                        
                        <div style='text-align: center; padding: 20px; color: #666;'>
                            <p>Thank you for your understanding and continued trust in SmileSlot Dental Clinic.</p>
                            <p style='font-size: 12px;'>This is an automated message, please do not reply to this email.</p>
                        </div>
                    </div>
                ";
            }

            $mail->send();
            echo json_encode(['success' => true, 'message' => 'Email notification sent successfully']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => "Email could not be sent. Mailer Error: {$mail->ErrorInfo}"]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Request not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
}
?> 