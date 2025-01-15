<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/animations.css">
    <link rel="stylesheet" href="css/finalmain.css">
    <link rel="stylesheet" href="css/signup.css">
    <link rel="stylesheet" href="css/navbar.css">
    <title>Create Account</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #ffffff, #e5d9f2);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 105vh;
            overflow: hidden;
        }

        .main-container {
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            display: flex;
            width: 80%;
            max-width: 600px;
            text-align: center;
            box-sizing: border-box;
            overflow: hidden;
            animation: transitionIn-X 0.5s;
        }

        .form-container {
            padding: 20px;
            box-sizing: border-box;
            width: 100%;
        }

        .header-text {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
        }

        .sub-text {
            font-size: 14px;
            color: #555;
            margin-bottom: 20px;
        }

        .form-body {
            margin-top: 20px;
        }

        .label-td {
            text-align: left;
            padding-bottom: 10px;
            width: 100%;
        }

        .form-label {
            font-size: 12px;
            color: #333;
        }

        .input-text {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        .login-btn {
            background-color: #6a11cb;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .login-btn:hover {
            background-color: #2575fc;
        }

        .hover-link1 {
            color: #6a11cb;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .hover-link1:hover {
            color: #2575fc;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <div class="logo">
            <a href="index.html" class="logo-link">
            <img src="img/Rimas_logo_png.png" alt="SmileSlot Logo" class="logo-img" />
            <span class="logo-title">SMILESLOT </span>
            <span class="logo-sub"> | RIMAS DENTAL CLINIC</span>
         </a>
        </div>
            <div class="nav-links">
                <a href="login.php">LOGIN</a>
                <a href="signup.php">REGISTER</a>
            </div>
        </div>
    </nav>
<body>
<?php

session_start();
$_SESSION["user"] = "";
$_SESSION["usertype"] = "";
date_default_timezone_set('Asia/manila');
$date = date('Y-m-d');
$_SESSION["date"] = $date;

include("connection.php");

if ($_POST) {
    // Ensure session variables for personal details are set
    if (!isset($_SESSION['personal'])) {
        $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Personal information is missing.</label>';
    } else {
        $fname = $_SESSION['personal']['fname'];
        $lname = $_SESSION['personal']['lname'];
        $name = $fname . " " . $lname;
        $address = $_SESSION['personal']['address'];
        
        $dob = $_SESSION['personal']['dob'];
        $email = $_POST['newemail'];
        $tele = $_POST['tele'];
        $newpassword = $_POST['newpassword'];
        $cpassword = $_POST['cpassword'];
        
        if ($newpassword == $cpassword) {
            // Check if email already exists
            $stmt = $database->prepare("SELECT * FROM webuser WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows == 1) {
                $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Already have an account for this Email address.</label>';
            } else {
                // Insert patient details
                $stmt = $database->prepare("INSERT INTO patient (pemail, pname, ppassword, paddress,  pdob, ptel) VALUES ( ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssss", $email, $name, $newpassword, $address, $dob, $tele);
                
                if ($stmt->execute()) {
                    // Insert into webuser table
                    $stmt = $database->prepare("INSERT INTO webuser (email, usertype) VALUES (?, 'p')");
                    $stmt->bind_param("s", $email);
                    $stmt->execute();
                    
                    // Set session variables
                    $_SESSION["user"] = $email;
                    $_SESSION["usertype"] = "p";
                    $_SESSION["username"] = $fname;

                    header('Location: patient/index.php');
                    exit(); // Add exit to stop further execution
                } else {
                    $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Error inserting data.</label>';
                }
            }
        } else {
            $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Password Confirmation Error! Reconfirm Password</label>';
        }
    }
} else {
    $error = '<label for="promter" class="form-label"></label>';
}

?>

<div class="main-container">
    <div class="form-container">
        <p class="header-text">Start Creating Your User Account</p>
        <p class="sub-text">Make Sure You Remember Your Login Information.</p>
        <div class="form-body">
            <form action="" method="POST">
                <div class="label-td">
                    <label for="newemail" class="form-label">Email:</label>
                </div>
                <div class="label-td">
                    <input type="email" name="newemail" class="input-text" placeholder="Email Address" required>
                </div>
                <div class="label-td">
                    <label for="tele" class="form-label">Mobile Number:</label>
                </div>
                <div class="label-td">
                    <input type="tel" name="tele" class="input-text"  pattern="[0]{1}[0-11]11}" placeholder="ex: 09123456789">
                </div>
                <div class="label-td">
                    <label for="newpassword" class="form-label">Create New Password:</label>
                </div>
                <div class="label-td">
                    <input type="password" name="newpassword" class="input-text" placeholder="New Password" required>
                </div>
                <div class="label-td">
                    <label for="cpassword" class="form-label">Confirm Password:</label>
                </div>
                <div class="label-td">
                    <input type="password" name="cpassword" class="input-text" placeholder="Confirm Password" required>
                </div>
                <div>
                    <?php echo $error ?>
                </div>
                <div>
                    <input type="reset" value="Reset" class="login-btn btn-primary-soft btn">
                </div>
                <div>
                    <input type="submit" value="Sign Up" class="login-btn btn-primary btn">
                </div>
            </form>
            <div>
                <br>
                <label for="" class="sub-text">Already have an account? </label>
                <a href="login.php" class="hover-link1">Login</a>
                <br><br><br>
            </div>
        </div>
    </div>
</div>
</body>
</html>
