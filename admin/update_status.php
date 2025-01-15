<?php
session_start();
include("../connection.php");

// Check if user is logged in and is admin
if(!isset($_SESSION["user"]) || $_SESSION['usertype'] != 'a'){
    header("location: ../login.php");
    exit();
}

if(isset($_GET['id']) && isset($_GET['status'])) {
    $id = $_GET['id'];
    $status = $_GET['status'];
    
    // Sanitize inputs
    $id = mysqli_real_escape_string($database, $id);
    $status = mysqli_real_escape_string($database, $status);
    
    // Update the status in the booking table
    $query = "UPDATE booking SET status = '$status' WHERE id = '$id'";
    
    if($database->query($query)) {
        // Success
        header("Location: appointment.php?update=success");
    } else {
        // Error
        header("Location: appointment.php?update=error");
    }
} else {
    header("Location: appointment.php");
}
?>
