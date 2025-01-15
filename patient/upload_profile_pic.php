<?php
// Start session to get the user details
session_start();

// Check if the user is logged in
if (!isset($_SESSION["user"])) {
    header("Location: ../login.php");
    exit;
}

// Include the database connection
include("../connection.php");

// Get user details
$useremail = $_SESSION["user"];

// Check if the file was uploaded
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_pic'])) {
    // Set the upload directory
    $uploadDir = '../uploads/';
    
    // Create the uploads directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Extract the file extension
    $fileExtension = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);

    // Generate a unique file name
    $uniqueFileName = uniqid("profile_", true) . "." . $fileExtension;

    // Set the target file path
    $filePath = $uploadDir . $uniqueFileName;

    // Move the uploaded file to the server
    if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $filePath)) {
        // Save the file path to the database
        $filePathForDB = '../uploads/' . $uniqueFileName; // Relative path for the database
        $sql = "UPDATE patient SET profile_pic = ? WHERE pemail = ?";
        $stmt = $database->prepare($sql);
        $stmt->bind_param("ss", $filePathForDB, $useremail);

        if ($stmt->execute()) {
            // Success
            header("Location: index.php?success=Profile picture updated successfully!");
        } else {
            // Database error
            echo "Error updating database: " . $stmt->error;
        }
    } else {
        echo "Error uploading file.";
    }
} else {
    echo "No file uploaded.";
}
?>
