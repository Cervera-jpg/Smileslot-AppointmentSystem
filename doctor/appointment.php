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
    <title>Appointments</title>
    <style>
        .popup{
            animation: transitionIn-Y-bottom 0.5s;
        }
        .sub-table{
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
        if(($_SESSION["user"])=="" or $_SESSION['usertype']!='d'){
            header("location: ../login.php");
        }else{
            $useremail=$_SESSION["user"];
        }

    }else{
        header("location: ../login.php");
    }
    
    

       //import database
       include("../connection.php");
       $userrow = $database->query("select * from doctor where docemail='$useremail'");
       $userfetch=$userrow->fetch_assoc();
       $userid= $userfetch["docid"];
       $username=$userfetch["docname"];
       $profilePicPath = $userfetch['profile_pic'] ?? 'default-profile.png';
    $fullPath = "../uploads/$profilePicPath";

    // Fallback to default image if the file does not exist
    if (!file_exists($fullPath)) {
        $fullPath = "../img/patient.png";
    }
    //echo $userid;
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
                                    <p class="profile-title"><?php echo substr($username,0,13)  ?></p>
                                    <p class="profile-subtitle"><?php echo substr($useremail,0,22)  ?></p>
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
                    <td class="menu-btn menu-icon-appoinment  menu-active menu-icon-appoinment-active">
                        <a href="appointment.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">My Appointments</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-patient">
                        <a href="patient.php" class="non-style-link-menu"><div><p class="menu-text">My Patients</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-settings">
                        <a href="settings.php" class="non-style-link-menu"><div><p class="menu-text">Settings</p></a></div>
                    </td>
                </tr>
                
            </table>
        </div>
        <div class="dash-body">
            <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;margin-top:55px; ">
                <tr >
                    <td width="13%" >
                    <a href="index.php" ><button  class="login-btn btn-primary-soft btn btn-icon-back"  style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px"><font class="tn-in-text">Back</font></button></a>
                    </td>
                    <td>
                        <p style="font-size: 23px;padding-left:12px;font-weight: 600;">Appointment Manager</p>
                                           
                    </td>
                    <td width="15%">
                        <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;text-align: right;">
                            Today's Date
                        </p>
                        <p class="heading-sub12" style="padding: 0;margin: 0;">
                            <?php 

                        date_default_timezone_set('Asia/Kolkata');

                        $today = date('Y-m-d');
                        echo $today;

                        $list110 = $database->query("select * from schedule inner join appointment on schedule.scheduleid=appointment.scheduleid inner join patient on patient.pid=appointment.pid inner join doctor on schedule.docid=doctor.docid  where  doctor.docid=$userid ");

                        ?>
                        </p>
                    </td>
                    <td width="10%">
                        <button  class="btn-label"  style="display: flex;justify-content: center;align-items: center;"><img src="../img/calendar.svg" width="100%"></button>
                    </td>


                </tr>
               
                <tr>
                    <td colspan="4" style="padding-top:10px;width: 100%;" >
                        <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)">My Appointments (<?php echo $list110->num_rows; ?>)</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="padding-top:0px;width: 100%;" >
                        <center>
                        <table class="filter-container" border="0" >
                        <tr>
                           <td width="10%">
                           </td> 
                        <td width="5%" style="text-align: center;">
                        Date:
                        </td>
                        <td width="30%">
                        <form action="" method="post">
                            <input type="date" name="scheduledate" id="date" class="input-text filter-container-items" style="margin: 0;width: 95%;">
                        </td>
                        <td width="12%">
                            <input type="submit" name="filter" value=" Filter" class="btn-primary-soft btn button-icon btn-filter" style="padding: 15px; margin:0;width:100%">
                        </form>
                        </td>
                        </tr>
                        </table>
                        </center>
                    </td>
                </tr>

                <tr>
                   <td colspan="4">
                       <center>
                        <div class="abc scroll">
                        <table width="93%" class="sub-table scrolldown" border="0" style="border:none">
                            <thead>
                                <tr>
                                    <th style="padding: 10px; text-align: left;">Patient Name</th>
                                    <th style="padding: 10px; text-align: left;">Date</th>
                                    <th style="padding: 10px; text-align: left;">Time</th>
                                    <th style="padding: 10px; text-align: left;">Description/Reason</th>
                                    <th style="padding: 10px; text-align: center;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $query = "SELECT b.*, p.pname, b.DATE, b.time_slot, b.dscrptn, b.did,b.status
                                         FROM booking b 
                                         JOIN patient p ON b.pid = p.pid 
                                         JOIN doctor d ON d.docid = b.did
                                         WHERE d.docid = $userid 
                                         ORDER BY b.DATE DESC";
                                $result = $database->query($query);

                                if ($result->num_rows > 0) {
                                    while($row = $result->fetch_assoc()) {
                                ?>
                                <tr>
                                    <td style="padding: 10px; text-align: left;"><?php echo $row['pname']; ?></td>
                                    <td style="padding: 10px; text-align: left;"><?php echo $row['DATE']; ?></td>
                                    <td style="padding: 10px; text-align: left;"><?php echo $row['time_slot']; ?></td>
                                    <td style="padding: 10px; text-align: left;"><?php echo $row['dscrptn']; ?></td>
                                    <td style="padding: 10px; text-align: left;"><?php echo $row['status']; ?></td>
                                    <td style="padding: 10px; text-align: center;">
                                        <?php 
                                        date_default_timezone_set('Asia/Manila');
                                        $currentDateTime = new DateTime();
                                        
                                        $appointmentDate = $row['DATE'];
                                        $timeSlot = $row['time_slot'];
                                        $timeStart = explode('-', $timeSlot)[0];
                                        
                                        $appointmentDateTime = new DateTime($appointmentDate . ' ' . $timeStart);
                                        
                                        ?>
                                    </td>
                                </tr>
                                <?php 
                                    }
                                } else {
                                    echo "<tr><td colspan='5' style='text-align:center;'>No appointments found</td></tr>";
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
   
    </div>
<script src="../script.js"></script>
</body>
</html>