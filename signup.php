<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/animations.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/signup.css">
    <link rel="stylesheet" href="css/navbar.css">   
    <title>Sign Up</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #ffffff, #e5d9f2);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        .main-container {
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            display: flex;
            width: 90%;
            max-width: 900px;
            text-align: center;
            box-sizing: border-box;
            overflow: hidden;
        }

        .section-container {
            flex: 1;
            padding: 20px;
            box-sizing: border-box;
        }

        .info-container {
            background-color: #f0f0f0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
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

        .info-content {
            font-size: 14px;
            color: #333;
            margin-bottom: 15px;
            text-align: center;
        }

        .info-image {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
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
date_default_timezone_set('Asia/Manila');
$date = date('Y-m-d');
$_SESSION["date"] = $date;

if ($_POST) {
    $_SESSION["personal"] = array(
        'fname' => $_POST['fname'],
        'lname' => $_POST['lname'],
        'address' => $_POST['address'],
        'dob' => $_POST['dob']
    );
    print_r($_SESSION["personal"]);
    header("location: create-account.php");
}

?>
<div class="main-container">
    <div class="section-container info-container">
        <p class="header-text">Welcome to Our Clinic</p>
        <p class="sub-text">We're excited to have you on board!</p>
        <div class="info-content">
            <p>Our clinic offers the best dental services with experienced professionals.</p>
            <p>Join us to experience top-notch dental care.</p>
        </div>
        <img src="img/Rimas_logo_png.png" alt="Dental Care" class="info-image">
    </div>
    
    <div class="section-container form-container">
        <form action="" method="POST">
            <p class="header-text">Let's Get Started</p>
            <p class="sub-text">Add Your Personal Details to Continue</p>

            <div class="form-body">
                <!-- First Name and Last Name -->
                <div class="label-td">
                    <label for="fname" class="form-label">First Name:</label>
                    <input type="text" name="fname" class="input-text" placeholder="First Name" required>
                </div>
                <div class="label-td">
                    <label for="lname" class="form-label">Last Name:</label>
                    <input type="text" name="lname" class="input-text" placeholder="Last Name" required>
                </div>

                <!-- Address -->
                <div class="label-td">
                    <label for="address" class="form-label">Address:</label>
                    <input type="text" name="address" class="input-text" placeholder="Address" required>
                </div>

                <!-- Date of Birth -->
                <div class="label-td">
                    <label for="dob" class="form-label">Date of Birth:</label>
                    <input type="date" name="dob" class="input-text" required>
                </div>

                <!-- Submit and Reset Buttons -->
                <div class="label-td">
                    <input type="reset" value="Reset" class="login-btn btn-primary-soft btn">
                </div>
                <div class="label-td">
                    <input type="submit" value="Next" class="login-btn btn-primary btn">
                </div>
            </div>

            <div>
                <br>
                <label class="sub-text">Already have an account? </label>
                <a href="login.php" class="hover-link1">Login</a>
                <br><br><br>
            </div>
        </form>
    </div>
</div>
</body>
</html>
