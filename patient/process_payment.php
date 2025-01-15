<?php
session_start();
include("../connection.php");

if (!isset($_SESSION["user"]) || $_SESSION["usertype"] != 'p') {
    header("location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $request_id = $_POST['request_id'];
    
    // First check if the request is approved
    $check_sql = "SELECT status FROM `request-table` WHERE id = ?";
    $check_stmt = $database->prepare($check_sql);
    $check_stmt->bind_param("i", $request_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    $request = $result->fetch_assoc();
    
    if (!$request || strtolower($request['status']) !== 'approved') {
        header("location: payment.php?error=not_approved");
        exit();
    }
    
    $current_date = date('Y-m-d H:i:s');
    
    // Update both status and payment date
    $update_sql = "UPDATE `request-table` 
                   SET status = 'paid',
                       payment_date = ?,
                       payment_method = ?
                   WHERE id = ? AND status = 'approved'";
                   
    $stmt = $database->prepare($update_sql);
    $payment_method = $_POST['payment_method'];
    $stmt->bind_param("ssi", $current_date, $payment_method, $request_id);
    
    if ($stmt->execute()) {
        header("location: payment.php?success=1");
    } else {
        header("location: payment.php?error=1");
    }
} else {
    header("location: payment.php");
}
?>
