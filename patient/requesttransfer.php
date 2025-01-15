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
        .form-input {
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-input:focus {
            outline: none;
            border-color: #6a11cb;
            box-shadow: 0 0 5px rgba(106, 17, 203, 0.2);
        }

        textarea {
            width: 100%;
            min-height: 100px;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            resize: vertical;
        }

        textarea:focus {
            outline: none;
            border-color: #6a11cb;
            box-shadow: 0 0 5px rgba(106, 17, 203, 0.2);
        }

        select {
            width: 100%;
            padding: 8px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        select:focus {
            outline: none;
            border-color: #6a11cb;
            box-shadow: 0 0 5px rgba(106, 17, 203, 0.2);
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
                    <td class="menu-btn menu-icon-request menu-active menu-icon-request-active ">
                        <a href="requesttransfer.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Request Transfer</p></a></div>
                    </td>
                </tr>

                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-payment">
                        <a href="payment.php" class="non-style-link-menu"><div><p class="menu-text">Payment</p></a></div>
                    </td>
                </tr>
                
            </table>
        </div>
        <div class="dash-body" style="margin-top: 15px">
            <table border="0" width="100%" style="border-spacing: 0;margin:0; margin-top:25px; padding:0;">
                <tr>
                    <td colspan="4">
                        <center>
                            <div class="referral-form" style="width:95%; margin-bottom:30px;">
                                <h3>Medical Referral Request</h3>
                                <form action="process_referral.php" method="POST">
                                    <div style="width:90%; margin:auto;">
                                        <!-- Patient Information -->
                                        <div class="patient-info" style="margin-bottom:20px;">
                                            <h4>Patient Information</h4>
                                            <p><strong>Name:</strong> <?php echo $username; ?></p>
                                            <p><strong>Email:</strong> <?php echo $useremail; ?></p>
                                            <p><strong>Date:</strong> <?php echo date('Y-m-d'); ?></p>
                                        </div>

                                        <!-- Referral Information -->
                                        <div style="margin-bottom: 20px;">
                                            <label for="referral_to">Referral To (Clinic/Hospital):</label>
                                            <input type="text" name="referral_to" id="referral_to" required 
                                                   class="form-input" style="width: 100%; padding: 8px; margin: 10px 0;">
                                        </div>

                                        <!-- Current Doctor Selection -->
                                        <div style="margin-bottom: 20px;">
                                            <label for="referring_doctor">Dentist Name:</label>
                                            <select name="referring_doctor" id="referring_doctor" required>
                                                <?php
                                                $sql = "SELECT DISTINCT d.docid, d.docname 
                                                       FROM doctor d 
                                                       INNER JOIN booking b ON d.docid = b.did 
                                                       WHERE b.email = '$useremail'";
                                                $result = $database->query($sql);
                                                while($row = $result->fetch_assoc()) {
                                                    echo "<option value='".$row['docid']."|".$row['docname']."'>".$row['docname']."</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                                
                                        <!-- Reason for Referral -->
                                        <div style="margin-bottom: 20px;">
                                            <label for="reason">Reason for Referral:</label>
                                            <textarea name="reason" id="reason" required 
                                                      placeholder="Please provide detailed reason for the referral request"></textarea>
                                        </div>

                                        <!-- Submit Button -->
                                        <div style="margin-bottom: 20px; text-align: center;">
                                            <input type="submit" value="Submit Referral Request" 
                                                   class="btn-primary btn" style="width: 250px;">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </center>
                    </td>
                </tr>
            </table>
        </div>
    </div>
<script src="../script.js"></script>


</body>
</html>