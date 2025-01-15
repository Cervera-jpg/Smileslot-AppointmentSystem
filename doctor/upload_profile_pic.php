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

// Check if the form is submitted and a file is uploaded
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
    // Set the upload directory
    $uploadDir = '../uploads/';
    
    // Create the uploads directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Extract the file extension and sanitize the file name
    $fileExtension = strtolower(pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION));
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

    // Validate file extension
    if (!in_array($fileExtension, $allowedExtensions)) {
        echo "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
        exit;
    }

    // Generate a unique file name
    $uniqueFileName = uniqid("profile_", true) . "." . $fileExtension;

    // Set the target file path
    $filePath = $uploadDir . $uniqueFileName;

    // Move the uploaded file to the server
    if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $filePath)) {
        // Save the file path to the database
        $filePathForDB = '../uploads/' . $uniqueFileName; // Adjusted to be relative for database
        $sql = "UPDATE doctor SET profile_pic = ? WHERE docemail = ?";
        $stmt = $database->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ss", $filePathForDB, $useremail);
            if ($stmt->execute()) {
                // Redirect on success
                header("Location: index.php?success=Profile picture updated successfully!");
                exit;
            } else {
                // Database error
                echo "Error updating database: " . $stmt->error;
            }
        } else {
            echo "Error preparing statement: " . $database->error;
        }
    } else {
        echo "Error moving the uploaded file.";
    }
} else {
    // Handle file upload errors
    if (isset($_FILES['profile_pic']['error'])) {
        switch ($_FILES['profile_pic']['error']) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                echo "The uploaded file exceeds the allowed size.";
                break;
            case UPLOAD_ERR_PARTIAL:
                echo "The file was only partially uploaded.";
                break;
            case UPLOAD_ERR_NO_FILE:
                echo "No file was uploaded.";
                break;
            default:
                echo "An unknown error occurred during file upload.";
        }
    } else {
        echo "No file uploaded.";
    }
}
?>
