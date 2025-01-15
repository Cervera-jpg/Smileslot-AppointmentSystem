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
    <title>Patients</title>
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
                                    <p class="profile-title"><?php echo substr($username,0,14)  ?></p>
                                    <p class="profile-subtitle"><?php echo substr($useremail,0,20)  ?></p>
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
                    <td class="menu-btn menu-icon-dashbord" >
                        <a href="index.php" class="non-style-link-menu "><div><p class="menu-text">Dashboard</p></a></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-appoinment">
                        <a href="appointment.php" class="non-style-link-menu"><div><p class="menu-text">My Appointments</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-patient menu-active menu-icon-patient-active">
                        <a href="patient.php" class="non-style-link-menu  non-style-link-menu-active"><div><p class="menu-text">My Patients</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-settings   ">
                        <a href="settings.php" class="non-style-link-menu"><div><p class="menu-text">Settings</p></a></div>
                    </td>
                </tr>
                
            </table>
        </div>
        <?php       

                    $selecttype="My";
                    $current="My patients Only";
                    if($_POST){

                        if(isset($_POST["search"])){
                            $keyword=$_POST["search12"];
                            
                            $sqlmain= "select * from patient where pemail='$keyword' or pname='$keyword' or pname like '$keyword%' or pname like '%$keyword' or pname like '%$keyword%' ";
                            $selecttype="my";
                        }
                        
                        if(isset($_POST["filter"])){
                            if($_POST["showonly"]=='all'){
                                $sqlmain= "select * from patient";
                                $selecttype="All";
                                $current="All patients";
                            }else{
                                $sqlmain= "select * from appointment inner join patient on patient.pid=appointment.pid inner join schedule on schedule.scheduleid=appointment.scheduleid where schedule.docid=$userid;";
                                $selecttype="My";
                                $current="My patients Only";
                            }
                        }
                    }else{
                        $sqlmain = "SELECT DISTINCT b.PATIENTNAME, b.PHONE, b.EMAIL, 
                                    GROUP_CONCAT(b.DATE ORDER BY b.DATE DESC) as appointments,
                                    COUNT(b.DATE) as visit_count
                                    FROM booking b 
                                    WHERE b.did = $userid 
                                    GROUP BY b.EMAIL
                                    ORDER BY MAX(b.DATE) DESC";
                        $selecttype="My";
                    }



                ?>
        <div class="dash-body">
            <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;margin-top:70px; ">
                <tr >
                    <td width="13%">

                    <a href="index.php" ><button  class="login-btn btn-primary-soft btn btn-icon-back"  style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px"><font class="tn-in-text">Back</font></button></a>
                        
                    </td>
                    <td>
                        
                        <form action="" method="post" class="header-search">

                            <input type="search" name="search12" class="input-text header-searchbar" placeholder="Search Patient name or Email" list="patient">&nbsp;&nbsp;
                            
                            <?php
                                echo '<datalist id="patient">';
                                $list11 = $database->query($sqlmain);
                               //$list12= $database->query("select * from appointment inner join patient on patient.pid=appointment.pid inner join schedule on schedule.scheduleid=appointment.scheduleid where schedule.docid=1;");

                                for ($y=0;$y<$list11->num_rows;$y++){
                                    $row00=$list11->fetch_assoc();
                                    $d=$row00["pname"];
                                    $c=$row00["pemail"];
                                    echo "<option value='$d'><br/>";
                                    echo "<option value='$c'><br/>";
                                };

                            echo ' </datalist>';
?>
                            
                       
                            <input type="Submit" value="Search" name="search" class="login-btn btn-primary btn" style="padding-left: 25px;padding-right: 25px;padding-top: 10px;padding-bottom: 10px;">
                        
                        </form>
                        
                    </td>
                    <td width="15%">
                        <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;text-align: right;">
                            Today's Date
                        </p>
                        <p class="heading-sub12" style="padding: 0;margin: 0;">
                            <?php 
                        date_default_timezone_set('Asia/Kolkata');

                        $date = date('Y-m-d');
                        echo $date;
                        ?>
                        </p>
                    </td>
                    <td width="10%">
                        <button  class="btn-label"  style="display: flex;justify-content: center;align-items: center;"><img src="../img/calendar.svg" width="100%"></button>
                    </td>


                </tr>
               
                
                <tr>
                    <td colspan="4" style="padding-top:10px;">
                        <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)"><?php echo $selecttype." Patients (".$list11->num_rows.")"; ?></p>
                    </td>
                    
                </tr>
                <tr>
                    <td colspan="4" style="padding-top:0px;width: 100%;" >
                        <center>
                        <table class="filter-container" border="0" >
 
                        <form action="" method="post">
                        
                        <td  style="text-align: right;">
                        Show Details About : &nbsp;
                        </td>
                        <td width="30%">
                        <select name="showonly" id="" class="box filter-container-items" style="width:90% ;height: 37px;margin: 0;" >
                                    <option value="" disabled selected hidden><?php echo $current   ?></option><br/>
                                    <option value="my">My Patients Only</option><br/>
                                    <option value="all">All Patients</option><br/>
                                    

                        </select>
                    </td>
                    <td width="12%">
                        <input type="submit"  name="filter" value=" Filter" class=" btn-primary-soft btn button-icon btn-filter"  style="padding: 15px; margin :0;width:100%">
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
                        <table width="93%" class="sub-table scrolldown"  style="border-spacing:0;">
                        <thead>
                        <tr>
                                <th class="table-headin" style="text-align: center;">Name</th>
                                <th class="table-headin" style="text-align: center;">Telephone</th>
                                <th class="table-headin" style="text-align: center;">Email</th>
                                <th class="table-headin" style="text-align: center;">Total Visits</th>
                                <th class="table-headin" style="text-align: center;">Last Visit</th>
                                <th class="table-headin" style="text-align: center;">Events</th>
                            </tr>
                        </thead>
                        <tbody>
                        
                            <?php
                            $result = $database->query($sqlmain);
                            if($result->num_rows == 0){
                                echo '<tr>
                                <td colspan="6">
                                <br><br><br><br>
                                <center>
                                <img src="../img/found.webp" width="25%">
                                
                                <br>
                                <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">No patients found!</p>
                                </center>
                                <br><br><br><br>
                                </td>
                                </tr>';
                                
                            } else {
                                while($row = $result->fetch_assoc()) {
                                    $appointments = explode(',', $row["appointments"]);
                                    $lastVisit = $appointments[0]; // First appointment in the sorted list
                                    
                                    echo '<tr>
                                        <td style="text-align: center;">'.
                                        substr($row["PATIENTNAME"],0,35)
                                        .'</td>
                                        <td style="text-align: center;">
                                        '.substr($row["PHONE"],0,12).'
                                        </td>
                                        <td style="text-align: center;">
                                        '.substr($row["EMAIL"],0,20).'
                                        </td>
                                        <td style="text-align: center;">
                                        '.$row["visit_count"].'
                                        </td>
                                        <td style="text-align: center;">
                                        '.$lastVisit.'
                                        </td>
                                        <td style="text-align: center;">
                                        <div style="display:flex;justify-content: center;">
                                        <a href="?action=view&email='.urlencode($row["EMAIL"]).'" class="non-style-link">
                                            <button class="btn-primary-soft btn button-icon btn-view" 
                                                    style="padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;">
                                                <font class="tn-in-text">View History</font>
                                            </button>
                                        </a>
                                        </div>
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
    if($_GET){
        $email = urldecode($_GET["email"]);
        $action = $_GET["action"];
        
        $sqlmain = "SELECT * FROM booking WHERE EMAIL='$email' AND did=$userid ORDER BY DATE DESC";
        $result = $database->query($sqlmain);
        
        if($result->num_rows > 0) {
            $firstRow = $result->fetch_assoc();
            $name = $firstRow["PATIENTNAME"];
            $email = $firstRow["EMAIL"];
            $phone = $firstRow["PHONE"];
            
            echo '
            <div id="popup1" class="overlay">
                    <div class="popup">
                    <center>
                        <a class="close" href="patient.php">&times;</a>
                        <div class="content">
                        </div>
                        <div class="scrollable-popup-content" style="display: flex;justify-content: center;max-height: 400px;overflow-y: auto;">
                        <table width="80%" class="sub-table scrolldown add-doc-form-container" border="0">
                            <tr>
                                <td>
                                    <p style="padding: 0;margin: 0;text-align: left;font-size: 25px;font-weight: 500;">Patient History</p><br><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="name" class="form-label">Name: '.$name.'</label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="Email" class="form-label">Email: '.$email.'</label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="Tele" class="form-label">Phone: '.$phone.'</label>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><hr></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <p style="font-weight: 500;">Appointment History:</p>
                                </td>
                            </tr>';
                                
            // Reset result pointer
            $result->data_seek(0);
            
            while($row = $result->fetch_assoc()) {
                echo '<tr>
                        <td colspan="2">
                            <div style="padding: 10px;background-color: #f9f9f9;margin: 5px;border-radius: 5px;">
                                <p style="margin: 0;"><strong>Date:</strong> '.$row["DATE"].'</p>
                                <p style="margin: 0;"><strong>Time:</strong> '.$row["time_slot"].'</p>
                                <p style="margin: 0;"><strong>Reason:</strong> '.$row["dscrptn"].'</p>
                            </div>
                        </td>
                    </tr>';
            }
            
            echo '<tr>
                    <td colspan="2">
                        <a href="patient.php"><input type="button" value="OK" class="login-btn btn-primary-soft btn" ></a>
                    </td>
                </tr>
                </table>
                </div>
                </center>
                <br><br>
            </div>
            </div>';
        }
    }

?>
</div>
<script src="../script.js"></script>
</body>
</html>