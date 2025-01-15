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
                    <td class="menu-btn menu-icon-home menu-active menu-icon-home-active" >
                        <a href="index.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Home</p></a></div></a>
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
                    <td class="menu-btn menu-icon-payment">
                        <a href="payment.php" class="non-style-link-menu"><div><p class="menu-text">Payment</p></a></div>
                    </td>
                </tr>
                
            </table>
        </div>
        <div class="dash-body" style="margin-top: 15px">
            <table border="0" width="100%" style=" border-spacing: 0;margin:0; margin-top:45px; padding:0;" >
                        <tr > 
                            <td colspan="1" class="nav-bar" >
                            <p style="font-size: 23px;padding-left:12px;font-weight: 600;margin-left:20px;">Home</p>
                          
                            </td>
                            <td width="25%">

                            </td>
                            <td width="15%">
                                <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;text-align: right;">
                                    Today's Date
                                </p>
                                <p class="heading-sub12" style="padding: 0;margin: 0;">
                                    <?php 
                                date_default_timezone_set('Asia/manila');
        
                                $today = date('Y-m-d');
                                echo $today;
                                $patientrow = $database->query("select  * from  patient;");
                                $doctorrow = $database->query("select  * from  doctor;");
                                $appointmentrow = $database->query("select  * from  appointment where appodate>='$today';");
                                $schedulerow = $database->query("select  * from  schedule where scheduledate='$today';");
                                ?>
                                </p>
                            </td>
                            <td width="10%">
                                <button  class="btn-label"  style="display: flex;justify-content: center;align-items: center;"><img src="../img/calendar.svg" width="100%"></button>
                            </td>
                        </tr>
                <tr>
                    <td colspan="4" >
                    <center>
                    <table class="filter-container doctor-header patient-header" style="border: none;width:95%" border="0" >
                    <tr>
                        <td >
                            <h3>Hello!</h3>
                            <h1><?php echo $username  ?></h1>
                            <p> Welcome to Rimas Dental Clinic! Here, we believe in the power of a confident smile. Our state-of-the-art facilities,
                                combined with a warm and welcoming atmosphere, ensure that your visit is as pleasant as it is effective. We’re not just about fixing teeth;
                                we’re about creating lasting relationships and empowering you with a smile that lights up the room.
                            <br><br>
                            </p>
                        </td>
                    </tr>
                    </table>
                    </center>
                </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <table border="0" width="100%"">
                            <tr>
                                <td width="50%">
                                    <center>
                                        <table class="filter-container" style="border: none;" border="0">
                                            <tr>
                                                <td colspan="4">
                                                    <p style="font-size: 20px;font-weight:600;padding-left: 12px; color: #6a11cb">Status</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 50%;">
                                                    <div class="dashboard-items" style="padding:20px; margin:auto; width:90%; display:flex; justify-content: space-between;  color: #6a11cb; min-height: 120px;">
                                                        <div>
                                                            <div class="h1-dashboard">
                                                                <?php echo $doctorrow->num_rows ?>
                                                            </div><br>
                                                            <div class="h3-dashboard">
                                                                Dentist
                                                            </div>
                                                        </div>
                                                        <a href="doctors.php">
                                                            <div class="btn-icon-back dashboard-icons" style="background-image: url('../img/dentist.png'); cursor:pointer; background-size: contain; background-repeat: no-repeat; background-position: center; width: 50px; height: 50px;"></div>
                                                        </a>
                                                    </div>
                                                </td>

                                                <td style="width: 50%;">
                                                    <div class="dashboard-items" style="padding:20px; margin:auto; width:90%; display:flex; justify-content: space-between; color: #6a11cb; min-height: 120px;">
                                                        <div>
                                                            <div class="h1-dashboard">
                                                                <?php echo $patientrow->num_rows ?>
                                                            </div><br>
                                                            <div class="h3-dashboard">
                                                                All Patient
                                                            </div>
                                                        </div>
                                                        <div class="btn-icon-back dashboard-icons" style="background-image: url('../img/patient.png'); background-size: contain; background-repeat: no-repeat; background-position: center; width: 50px; height: 50px;"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 50%;">
                                                    <div class="dashboard-items" style="padding:20px; margin:auto; width:90%; display:flex; justify-content: space-between; color: #6a11cb; min-height: 120px;">
                                                        <div>
                                                            <div class="h1-dashboard">
                                                                <?php echo $appointmentrow->num_rows ?>
                                                            </div><br>
                                                            <div class="h3-dashboard">
                                                                My Booking
                                                            </div>
                                                        </div>
                                                        <a href="schedule.php">
                                                            <div class="btn-icon-back dashboard-icons" style="background-image: url('../img/bookings.png'); cursor:pointer; background-size: contain; background-repeat: no-repeat; background-position: center; width: 50px; height: 50px;"></div>
                                                        </a>
                                                    </div>
                                                </td>
                                                <td style="width: 50%;">
                                                    <div class="dashboard-items" style="padding:20px; margin:auto; width:90%; display:flex; justify-content: space-between;  color: #6a11cb; min-height: 120px;">
                                                        <div>
                                                            <div class="h1-dashboard">
                                                                <?php echo $schedulerow->num_rows ?>
                                                            </div><br>
                                                            <div class="h3-dashboard">
                                                                Today's Session
                                                            </div>
                                                        </div>
                                                        <a href="appointment.php">
                                                            <div class="btn-icon-back dashboard-icons" style="background-image: url('../img/sessions.png'); cursor:pointer; background-size: contain; background-repeat: no-repeat; background-position: center; width: 50px; height: 50px;"></div>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </center>
                                </td>
                                
                            </tr>
                        </table>
                    </td>
                <tr>
            </table>
        </div>
    </div>
<script src="../script.js"></script>
</body>
</html>