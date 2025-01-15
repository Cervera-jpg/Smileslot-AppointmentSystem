<?php
session_start();
include("../connection.php");

// Check if user is logged in and is admin
if(!isset($_SESSION["user"]) || $_SESSION['usertype'] != 'a'){
    header("location: ../login.php");
    exit();
}

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Sanitize input
    $id = mysqli_real_escape_string($database, $id);
    
    // Delete the appointment from the booking table
    $query = "DELETE FROM booking WHERE id = '$id'";
    
    if($database->query($query)) {
        // Success
        header("Location: appointment.php?delete=success");
    } else {
        // Error
        header("Location: appointment.php?delete=error");
    }
} else {
    header("Location: appointment.php");
}
?>