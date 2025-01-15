<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/finalmain.css">  
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <title>Dashboard</title>
    <style>
        .dashbord-tables{
            animation: transitionIn-Y-over 0.5s;
        }
        .filter-container{
            animation: transitionIn-Y-bottom  0.5s;
        }
        .sub-table{
            animation: transitionIn-Y-bottom 0.5s;
        }
        .btn-approve {
            background-color: #00a884;
            color: white;
        }

        .btn-reject {
            background-color: #ff4646;
            color: white;
        }

        .btn-approve:hover {
            background-color: #008f6f;
        }

        .btn-reject:hover {
            background-color: #e60000;
        }

        .abc {
            width: 100%;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <div class="logo">
            <a href="index.php" class="logo-link">
            <img src="../img/Rimas_logo_png.png" alt="SmileSlot Logo" class="logo-img" />
            <span class="logo-title">SMILESLOT </span>
            <span class="logo-sub"> | RIMAS DENTAL CLINIC</span>
         </a>
        </div>
    </nav>
</body>
<body>
    <?php



    session_start();

    if(isset($_SESSION["user"])){
        if(($_SESSION["user"])=="" or $_SESSION['usertype']!='a'){
            header("location: ../login.php");
        }

    }else{
        header("location: ../login.php");
    }
    

    //import database
    include("../connection.php");

    
    ?>
    <div class="container">
        <div class="menu">
            <table class="menu-container" border="0">
                <tr>
                    <td style="padding:10px" colspan="2">
                        <table border="0" class="profile-container">
                            <tr>
                                <td width="30%" style="padding-left:20px" >
								<a href="index.php">
								<img src="../img/admin1.png" alt="" width="100%" style="border-radius:50%; cursor: pointer;">
								</a>
                                    
                                </td>
                                <td style="padding:0px;margin:0px;">
                                    <p class="profile-title">Administrator</p>
                                    <p class="profile-subtitle">admin@smileslot.com</p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <a href="../logout.php" ><input type="button" value="Log out" class="logout-btn btn-primary-soft btn"></a>
                                </td>
                            </tr>
                    </table>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-dashbord " >
                        <a href="index.php" class="non-style-link-menu "><div><p class="menu-text">Dashboard</p></a></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-doctor ">
                        <a href="doctors.php" class="non-style-link-menu "><div><p class="menu-text">Dentist</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-appoinment">
                        <a href="appointment.php" class="non-style-link-menu"><div><p class="menu-text">Appointment</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-patient">
                        <a href="patient.php" class="non-style-link-menu"><div><p class="menu-text">Patients</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-request menu-active menu-icon-request-active">
                        <a href="requesttransfer.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Request Transfer</p></a></div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="dash-body" style="margin-top: 15px">
            <table border="0" width="100%" style="border-spacing: 0;margin:0;padding:0;">
                <tr>
                    <td colspan="4">
                        <center>
                            <h3>Request Transfer List</h3>
                            <div class="abc scroll">
                                <table width="93%" class="sub-table scrolldown" border="0">
                                    <thead>
                                        <tr>
                                            <th class="table-headin">Patient Name</th>
                                            <th class="table-headin">Patient Email</th>
                                            <th class="table-headin">Reason</th>
                                            <th class="table-headin">Referral To</th>
                                            <th class="table-headin">Doctor Name</th>
                                            <th class="table-headin">Status</th>
                                            <th class="table-headin">Request Date</th>
                                            <th class="table-headin">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $query = "SELECT *, DATE_FORMAT(request_date, '%M %d, %Y %h:%i %p') as formatted_date 
                                                  FROM `request-table` ORDER BY id DESC";
                                        $result = $database->query($query);

                                        if($result->num_rows == 0) {
                                            echo '<tr>
                                                <td colspan="8">
                                                    <center>
                                                        <img src="../img/notfound.svg" width="25%">
                                                        <br>
                                                        <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">No request transfers found!</p>
                                                    </center>
                                                </td>
                                            </tr>';
                                        } else {
                                            while($row = $result->fetch_assoc()) {
                                                echo '<tr>
                                                    <td>'.substr($row['pname'],0,30).'</td>
                                                    <td>'.substr($row['pemail'],0,30).'</td>
                                                    <td>'.substr($row['reason'],0,30).'</td>
                                                    <td>'.substr($row['referralto'],0,30).'</td>
                                                    <td>'.substr($row['dname'],0,30).'</td>
                                                    <td>'.$row['status'].'</td>
                                                    <td>'.$row['formatted_date'].'</td>
                                                    <td>
                                                        <div style="display:flex;justify-content: center;">';
                                                        
                                                if(strtolower($row['status']) == 'pending') {
                                                    echo '<div style="display:flex;justify-content: center;gap:10px;">
                                                            <a href="?action=approve&id='.$row['id'].'" class="non-style-link">
                                                                <button class="btn-primary-soft btn button-icon btn-approve" style="padding-left: 20px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;">
                                                                    <font class="tn-in-text">Approve</font>
                                                                </button>
                                                            </a>
                                                            <a href="?action=reject&id='.$row['id'].'" class="non-style-link">
                                                                <button class="btn-primary-soft btn button-icon btn-reject" style="padding-left: 20px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;">
                                                                    <font class="tn-in-text">Reject</font>
                                                                </button>
                                                            </a>
                                                          </div>';
                                                } else if(strtolower($row['status']) == 'approved' || strtolower($row['status']) == 'paid') {
                                                    echo '<div style="display:flex;justify-content: center;gap:10px;">
                                                            <button onclick="showPrintModal('.$row['id'].')" class="btn-primary-soft btn button-icon btn-print" style="padding-left: 20px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;">
                                                                <font class="tn-in-text">Print Form</font>
                                                            </button>
                                                            <button onclick="sendEmailNotification('.$row['id'].', \'approve\')" class="btn-primary-soft btn button-icon btn-notify" style="padding-left: 20px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;">
                                                                <font class="tn-in-text">Notify Patient</font>
                                                            </button>
                                                          </div>';
                                                } else if(strtolower($row['status']) == 'rejected') {
                                                    echo '<div style="display:flex;justify-content: center;gap:10px;">
                                                            <button onclick="sendEmailNotification('.$row['id'].', \'reject\')" class="btn-primary-soft btn button-icon btn-notify" style="padding-left: 20px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;">
                                                                <font class="tn-in-text">Notify Patient</font>
                                                            </button>
                                                          </div>';
                                                }
                                                
                                                echo '</div>
                                                    </td>
                                                </tr>';
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </center>
                    </td>
                </tr>
            </table>
        </div>
    </div>

<?php
// Handle approve/reject actions
if(isset($_GET['action']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];
    $newStatus = ($action == 'approve') ? 'Approved' : 'Rejected';
    
    $updateQuery = "UPDATE `request-table` SET status='$newStatus' WHERE id=$id";
    if($database->query($updateQuery)) {
        echo '<script>
            alert("Request has been '.$newStatus.'!");
            window.location.href = "requesttransfer.php";
        </script>';
    }
}
?>

<!-- Print Modal -->
<div id="printModal" class="modal">
    <div class="modal-content" style="width:800px;">
        <div class="modal-header">
            <span class="close-modal" onclick="closeModal('printModal')">&times;</span>
        </div>
        <div class="modal-body" id="printArea">
            <div class="header" style="text-align:center; margin-bottom:30px;">
                <img src="../img/Rimas_logo_png.png" alt="SmileSlot Logo" style="width:150px; margin-bottom:10px;">
                <h2>DENTAL REFERRAL FORM</h2>
                <h3>SMILESLOT | RIMAS DENTAL CLINIC</h3>
            </div>

            <div class="content">
                <table style="width:100%; margin-bottom:20px;">
                    <tr>
                        <td><strong>Date:</strong> <span id="print-date"></span></td>
                        <td><strong>Request Date:</strong> <span id="print-request-date"></span></td>
                    </tr>
                </table>

                <div style="border:1px solid #ddd; padding:15px; margin-bottom:20px;">
                    <h4>Patient Information</h4>
                    <p><strong>Name:</strong> <span id="print-pname"></span></p>
                    <p><strong>Email:</strong> <span id="print-pemail"></span></p>
                </div>

                <div style="border:1px solid #ddd; padding:15px; margin-bottom:20px;">
                    <h4>Referral Details</h4>
                    <p><strong>Referring To:</strong> <span id="print-referralto"></span></p>
                    <p><strong>Referring Dentist:</strong> <span id="print-dname"></span></p>
                    <p><strong>Reason for Referral:</strong></p>
                    <p id="print-reason" style="padding:10px; background:#f9f9f9;"></p>
                </div>

                <!-- Add Patient History section before signatures -->
                <div style="text-align:left; margin-bottom:20px;">
                    <h4 style="margin-bottom:10px;">Patient History</h4>
                    <div style="margin-left:20px;">
                        <table class="history-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Dentist</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="patient-history">
                                <!-- Will be populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <div style="display: flex; justify-content: space-between; margin-top: 50px;">
                    <div style="text-align:center;">
                        <div style="border-top: 1px solid #000; margin-top: 100px; width: 200px;"></div>
                        <p>Patient's Signature</p>
                        <p class="date-field">Date: <?php echo date('F d, Y'); ?></p>
                    </div>
                    <div style="text-align:center;">
                        <img src="../img/e-sig-rimas.jpg" alt="Doctor's Signature" style="width: 200px; height: auto; margin-bottom: 5px;">
                        <div style="border-top: 1px solid #000; width: 200px;"></div>
                        <p>Dentist's Signature</p>
                        <p class="date-field">Date: <?php echo date('F d, Y'); ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button onclick="printReferral()" class="btn-primary-soft btn" style="margin-right: 10px;">
                <i class="fas fa-print"></i> Print Form
            </button>
            <button onclick="downloadReferral()" class="btn-primary-soft btn">
                <i class="fas fa-download"></i> Download PDF
            </button>
        </div>
    </div>
</div>

<!-- Add this CSS -->
<style>
.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.4);
    overflow-y: auto;
}

.modal-content {
    background-color: #fefefe;
    margin: 20px auto;
    padding: 20px;
    border: 1px solid #888;
    width: 90%;
    max-width: 900px;
}

.modal-body {
    padding: 20px;
    overflow-x: auto;
}

.close-modal {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    position: sticky;
    top: 0;
    right: 0;
}

.close-modal:hover {
    color: black;
}

.modal-header {
    position: sticky;
    top: 0;
    background-color: #fefefe;
    padding: 10px 0;
    z-index: 1;
}

.modal-footer {
    position: sticky;
    bottom: 0;
    background-color: #fefefe;
    padding: 10px 0;
    z-index: 1;
}

@media print {
    /* Hide everything except the print area */
    body > *:not(#printModal) {
        display: none;
    }
    
    /* Show only the print content */
    #printModal {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: visible;
        background-color: white;
    }
    
    .modal-content {
        box-shadow: none;
        border: none;
        padding: 0;
        margin: 0;
        width: 100%;
    }
    
    #printArea {
        display: block !important;
        position: static;
        padding: 20px;
        width: 100%;
    }
    
    /* Hide modal elements we don't want to print */
    .modal-header,
    .modal-footer,
    .close-modal {
        display: none !important;
    }
    
    /* Ensure images are visible */
    #printArea img {
        display: block !important;
        max-width: 150px;
        margin: 0 auto;
    }
    
    /* Ensure text is visible */
    #printArea * {
        color: black !important;
        visibility: visible !important;
    }
}

/* Regular modal styles */
.modal-content {
    background-color: #fefefe;
    margin: 20px auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 800px;
}

#printArea {
    padding: 20px;
    background: white;
}

#printArea .header img {
    max-width: 150px;
    height: auto;
}

#printArea .content {
    margin: 20px 0;
}

/* Table styles */
.history-table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
}

.history-table th,
.history-table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
    word-wrap: break-word;
}

/* Column widths */
.history-table th:nth-child(1),
.history-table td:nth-child(1) {
    width: 15%;
}

.history-table th:nth-child(2),
.history-table td:nth-child(2) {
    width: 15%;
}

.history-table th:nth-child(3),
.history-table td:nth-child(3) {
    width: 25%;
}

.history-table th:nth-child(4),
.history-table td:nth-child(4) {
    width: 25%;
}

.history-table th:nth-child(5),
.history-table td:nth-child(5) {
    width: 20%;
}

@media print {
    .modal {
        position: absolute;
        height: auto;
        overflow: visible;
    }
    
    .modal-content {
        margin: 0;
        padding: 0;
        width: 100%;
    }
    
    .modal-body {
        padding: 20px;
        overflow: visible;
    }
    
    .history-table {
        page-break-inside: auto;
    }
    
    .history-table tr {
        page-break-inside: avoid;
    }
    
    .history-table th {
        background-color: #f5f5f5 !important;
        -webkit-print-color-adjust: exact;
    }
}
</style>

<!-- Add this JavaScript -->
<script>
function showPrintModal(id) {
    fetch(`get_referral.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            console.log('Received data:', data);
            
            if (data.error) {
                console.error('Server error:', data);
                alert('Error: ' + data.error);
                return;
            }
            
            try {
                // Populate all fields
                document.getElementById('print-date').textContent = new Date().toLocaleDateString();
                document.getElementById('print-request-date').textContent = data.request_date || '';
                document.getElementById('print-pname').textContent = data.pname || '';
                document.getElementById('print-pemail').textContent = data.pemail || '';
                document.getElementById('print-referralto').textContent = data.referralto || '';
                document.getElementById('print-dname').textContent = data.dname || '';
                document.getElementById('print-reason').textContent = data.reason || '';
                
                // Populate patient history
                const historyTableBody = document.getElementById('patient-history');
                historyTableBody.innerHTML = ''; // Clear existing content
                
                if (data.history && data.history.length > 0) {
                    data.history.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td style="border: 1px solid #ddd; padding: 8px;">${record.DATE || ''}</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">${record.time_slot || ''}</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">${record.dname || ''}</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">${record.dscrptn || ''}</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">${record.status || ''}</td>
                        `;
                        historyTableBody.appendChild(row);
                    });
                } else {
                    historyTableBody.innerHTML = `
                        <tr>
                            <td colspan="5" style="border: 1px solid #ddd; padding: 8px; text-align: center;">
                                No history available
                            </td>
                        </tr>
                    `;
                }
                
                // Show the modal
                document.getElementById('printModal').style.display = 'block';
            } catch (error) {
                console.error('Error processing data:', error);
                alert('Error processing data: ' + error.message);
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert('Failed to load referral data: ' + error.message);
        });
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

function printReferral() {
    window.print();
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target.className === 'modal') {
        event.target.style.display = 'none';
    }
}
</script>

<!-- Add additional print-specific styles -->
<style>
@media print {
    #printArea {
        padding: 20px;
    }
    
    #printArea .header img {
        max-width: 100px;
    }
    
    #printArea .content {
        font-size: 12pt;
        line-height: 1.5;
    }
    
    #printArea table {
        page-break-inside: avoid;
    }
    
    .modal-header,
    .modal-footer,
    .close-modal {
        display: none;
    }
}
</style>


<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
function downloadReferral() {
    // Get the print area element
    const element = document.getElementById('printArea');
    
    // Configure the PDF options
    const opt = {
        margin: 1,
        filename: 'referral-form.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
    };

    // Generate and download the PDF
    html2pdf().set(opt).from(element).save();
}
</script>

<script>
function sendEmailNotification(id, type) {
    fetch(`send_email_notification.php?id=${id}&type=${type}`)
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert('Email notification sent successfully!');
            } else {
                alert('Failed to send email: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to send email notification');
        });
}
</script>

<style>
.btn-notify {
    background-color: #4CAF50;
    color: white;
}

.btn-notify:hover {
    background-color: #45a049;
}
</style>
</body>
</html>