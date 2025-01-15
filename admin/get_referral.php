<?php
session_start();
include("../connection.php");

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    if(isset($_SESSION["user"]) && $_SESSION['usertype']=='a' && isset($_GET['id'])) {
        $id = intval($_GET['id']);
        
        // Get referral data
        $referralQuery = "SELECT rt.*, b.DATE, b.time_slot, b.dname, b.dscrptn, b.status, DATE_FORMAT(rt.request_date, '%M %d, %Y %h:%i %p') as formatted_request_date 
                         FROM `request-table` rt
                         LEFT JOIN booking b ON rt.pemail = b.EMAIL
                         WHERE rt.id = ?";
        
        $stmt = $database->prepare($referralQuery);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $database->error);
        }
        
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        
        $result = $stmt->get_result();
        if (!$result) {
            throw new Exception("Get result failed: " . $stmt->error);
        }

        $referralData = $result->fetch_assoc();
        if ($referralData) {
            // Get patient's booking history separately
            $historyQuery = "SELECT DATE, time_slot, dname, dscrptn, status 
                           FROM booking 
                           WHERE EMAIL = ? 
                           ORDER BY DATE DESC";
            
            $historyStmt = $database->prepare($historyQuery);
            if (!$historyStmt) {
                throw new Exception("History prepare failed: " . $database->error);
            }
            
            $historyStmt->bind_param("s", $referralData['pemail']);
            if (!$historyStmt->execute()) {
                throw new Exception("History execute failed: " . $historyStmt->error);
            }
            
            $historyResult = $historyStmt->get_result();
            if (!$historyResult) {
                throw new Exception("History get result failed: " . $historyStmt->error);
            }
            
            // Add booking history to referral data
            $referralData['history'] = array();
            while($row = $historyResult->fetch_assoc()) {
                $referralData['history'][] = $row;
            }
            
            header('Content-Type: application/json');
            echo json_encode($referralData);
        } else {
            throw new Exception("No referral found with ID: " . $id);
        }
    } else {
        throw new Exception("Unauthorized access or missing ID");
    }
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode([
        "error" => $e->getMessage(),
        "debug" => [
            "session" => isset($_SESSION["user"]),
            "usertype" => $_SESSION['usertype'] ?? 'none',
            "id" => $_GET['id'] ?? 'none'
        ]
    ]);
}
?>