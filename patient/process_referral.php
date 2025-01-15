<?php
session_start();
include("../connection.php");

if (!isset($_SESSION["user"]) || $_SESSION["usertype"] != 'p') {
    header("location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get patient information from session
    $useremail = $_SESSION["user"];
    $patientQuery = $database->query("SELECT * FROM patient WHERE pemail='$useremail'");
    $patientData = $patientQuery->fetch_assoc();
    $pid = $patientData["pid"];
    $pname = $patientData["pname"];

    // Get form data
    $referral_to = $_POST['referral_to'];
    $doctor_info = explode('|', $_POST['referring_doctor']);
    $did = $doctor_info[0];
    $dname = $doctor_info[1];
    $reason = $_POST['reason'];
    $status = 'Pending';

    // Insert referral request
    $sql = "INSERT INTO `request-table` (pid, pemail, pname, reason, status, referralto, did, dname) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $database->prepare($sql);
    $stmt->bind_param("isssssss", $pid, $useremail, $pname, $reason, $status, $referral_to, $did, $dname);
    
    if ($stmt->execute()) {
        header("location: index.php?action=transfer_success");
        exit();
    } else {
        header("location: requesttransfer.php?action=request_error");
        exit();
    }
}
?>
