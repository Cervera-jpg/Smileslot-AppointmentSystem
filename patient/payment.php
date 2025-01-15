<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/finalmain.css?v=<?php echo time();?>"/>  
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
        .sub-table,.anime{
            animation: transitionIn-Y-bottom 0.5s;
        }
      
        .sub-table {
            border-collapse: collapse;
            width: 100%;
        }

        .sub-table th, .sub-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .sub-table th {
            background-color: #f5f5f5;
            font-weight: 600;
        }

        .sub-table tr:hover {
            background-color: #f9f9f9;
        }

        .paid-status {
            color: #28a745;
            font-weight: 500;
        }


        .btn-primary-soft:hover {
            background-color: #0056b3;
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
    //learn from w3schools.com

    session_start();

    if(isset($_SESSION["user"])){
        if(($_SESSION["user"])=="" or $_SESSION['usertype']!='p'){
            header("location: ../login.php");
        }else{
            $useremail=$_SESSION["user"];
        }

    }else{
        header("location: ../login.php");
    }
    
    //import database
    include("../connection.php");
    $userrow = $database->query("select * from patient where pemail='$useremail'");
    $userfetch=$userrow->fetch_assoc();
    $userid= $userfetch["pid"];
    $username=$userfetch["pname"];
    $profilePicPath = $userfetch['profile_pic'] ?? 'default-profile.png';
    $fullPath = "../uploads/$profilePicPath";

    // Fallback to default image if the file does not exist
    if (!file_exists($fullPath)) {
        $fullPath = "../img/patient.png";
    }

    //echo $userid;
    //echo $username;
    
    ?>
    <div class="container">
        <div class="menu">
            <table class="menu-container" border="0">
                <tr>
                    <td style="padding:10px" colspan="2">
                        <table border="0" class="profile-container">
                            <tr>
                           <td width="30%" style="padding-left:20px">
                                    <form id="profilePicForm" action="upload_profile_pic.php" method="POST" enctype="multipart/form-data">
                                        <div class="profile-pic-container">
                                            <label for="profilePicInput">
                                               <img id="profilePic" src="<?php echo htmlspecialchars($profilePicPath, ENT_QUOTES, 'UTF-8'); ?>" 
         alt="Profile Picture" class="profile-pic">
                                                <div class="profile-overlay">
                                                    <p>Change Picture</p>
                                                </div>
                                            </label>
                                            <input 
                                                type="file" 
                                                id="profilePicInput" 
                                                name="profile_pic" 
                                                style="display: none;" 
                                                accept="image/*" 
                                                onchange="submitForm(event)">
                                        </div>
                                    </form>
                            </td>
                                <td style="padding:0px;margin:0px;">
                                    <p class="profile-title"><?php echo substr($username,0,30)  ?></p>
                                    <p class="profile-subtitle"><?php echo substr($useremail,0,50)  ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <a href="../logout.php" ><input type="button" value="Log out" class="logout-btn btn-primary-soft btn"></a>
                                <style>
                                .logout-btn:hover {
                                    background-color: #6a11cb;
                                }
                                </style>
                                </td>
                            </tr>
                    </table>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-home" >
                        <a href="index.php" class="non-style-link-menu"><div><p class="menu-text">Home</p></a></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-doctor">
                        <a href="doctors.php" class="non-style-link-menu"><div><p class="menu-text">Dentist</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row">
                <td class="menu-btn menu-icon-schedule">
                    <a href="schedule.php" class="non-style-link-menu">
                        <div><p class="menu-text">Scheduled Sessions</p></div>
                    </a>
                </td>
            </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-appoinment">
                        <a href="appointment.php" class="non-style-link-menu"><div><p class="menu-text">My Appointments</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-settings">
                        <a href="settings.php" class="non-style-link-menu"><div><p class="menu-text">Settings</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-request">
                        <a href="requesttransfer.php" class="non-style-link-menu"><div><p class="menu-text">Request Transfer</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-payment  menu-active menu-icon-payment-active">
                        <a href="payment.php" class="non-style-link-menu  non-style-link-menu-active"><div><p class="menu-text">Payment</p></a></div>
                    </td>
                </tr>
                
            </table>
        </div>
       
            
                  
      
        <div class="dash-body">
            <table border="0" width="100%" style="border-spacing: 0;margin:0;padding:0;">
                <tr>
                    <td colspan="2" class="nav-bar">
                        <p style="font-size: 23px;padding-left:12px;font-weight: 600;margin-left:20px;">Payment History</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <center>
                            <div class="abc scroll">
                                <table width="93%" class="sub-table scrolldown" border="0">
                                    <thead>
                                        <tr>
                                            <th class="table-headin">Request ID</th>
                                            <th class="table-headin">Amount</th>
                                            <th class="table-headin">Status</th>
                                            <th class="table-headin">Request Date</th>
                                            <th class="table-headin">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $pending_payments = $database->query(
                                            "SELECT 
                                                rt.id,
                                                rt.status,
                                                rt.request_date
                                            FROM `request-table` rt 
                                            WHERE rt.pid = $userid 
                                            ORDER BY rt.request_date DESC"
                                        );

                                        if($pending_payments && $pending_payments->num_rows > 0) {
                                            while($payment = $pending_payments->fetch_assoc()) {
                                                ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($payment['id']); ?></td>
                                                    <td>₱500.00</td>
                                                    <td><?php echo ucfirst(htmlspecialchars($payment['status'])); ?></td>
                                                    <td>
                                                        <?php 
                                                            echo ($payment['request_date']) 
                                                                ? date('Y-m-d', strtotime($payment['request_date'])) 
                                                                : date('Y-m-d'); 
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php if(strtolower($payment['status']) === 'approved'): ?>
                                                            <button onclick="showPaymentModal(<?php echo $payment['id']; ?>, 500)" 
                                                                    class="btn-primary-soft btn">
                                                                Pay Now
                                                            </button>
                                                        <?php else: ?>
                                                            <?php if(strtolower($payment['status']) === 'paid'): ?>
                                                                <span class="paid-status">Paid</span>
                                                            <?php else: ?>
                                                                <span>Pending Approval</span>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            echo "<tr><td colspan='5' style='text-align: center;'>No payments found</td></tr>";
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

    <!-- Payment Modal -->
    <div id="paymentModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Payment Details</h2>
            <form id="paymentForm" action="process_payment.php" method="POST">
                <input type="hidden" id="requestId" name="request_id">
                <input type="hidden" id="amount" name="amount">
                
                <div class="form-group">
                    <label>Payment Method:</label>
                    <select name="payment_method" required>
                        <option value="gcash">GCash</option>
                        <option value="credit_card">Credit Card</option>
                    </select>
                </div>

                <div id="gcashFields">
                    <div class="form-group">
                        <label>Reference Number:</label>
                        <input type="text" name="reference_number" pattern="[0-9]+" minlength="8">
                    </div>
                </div>

                <div id="creditCardFields" style="display:none;">
                    <div class="form-group">
                        <label>Card Number:</label>
                        <input type="text" name="card_number" pattern="[0-9]+" maxlength="16">
                    </div>
                    <div class="form-group">
                        <label>Expiry Date:</label>
                        <input type="text" name="card_expiry" placeholder="MM/YY">
                    </div>
                </div>

                <button type="submit" class="btn-primary-soft btn">Submit Payment</button>
            </form>
        </div>
    </div>

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
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>

    <script>
        function showPaymentModal(requestId, amount) {
            document.getElementById('paymentModal').style.display = 'block';
            document.getElementById('requestId').value = requestId;
            document.getElementById('amount').value = amount;
        }

        // Close modal when clicking the X
        document.querySelector('.close').onclick = function() {
            document.getElementById('paymentModal').style.display = 'none';
        }

        // Toggle payment fields based on selected method
        document.querySelector('select[name="payment_method"]').onchange = function(e) {
            const gcashFields = document.getElementById('gcashFields');
            const creditCardFields = document.getElementById('creditCardFields');
            
            if(e.target.value === 'gcash') {
                gcashFields.style.display = 'block';
                creditCardFields.style.display = 'none';
            } else {
                gcashFields.style.display = 'none';
                creditCardFields.style.display = 'block';
            }
        }
    </script>
<script src="../script.js"></script>
</body>
</html>