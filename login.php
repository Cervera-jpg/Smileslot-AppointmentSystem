<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/animations.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/navbar.css">
    <title>Login</title>
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
        }
        
        .container {
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 40px;
            width: 400px;
            text-align: center;
        }

        .header-text {
            font-size: 32px;
            color: #333;
            margin-bottom: 10px;
        }
        .sub-text {
            font-size: 16px;
            color: #555;
            margin-bottom: 20px;
        }

        .form-body {
            margin-top: 20px;
        }

        .label-td {
            text-align: left;
            padding-bottom: 10px;
        }

        .form-label {
            font-size: 14px;
            color: #333;
        }

        .input-text {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            margin-bottom: 20px;
            box-sizing: border-box;
        }

        .login-btn {
            background-color: #6a11cb;
            color: white;
            border: none;
            padding: 15px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
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

        .error-message {
            color: rgb(255, 62, 62);
            text-align: center;
            margin-bottom: 20px;
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

    <?php
    session_start();
    $_SESSION["user"]="";
    $_SESSION["usertype"]="";
    date_default_timezone_set('Asia/Kolkata');
    $date = date('Y-m-d');
    $_SESSION["date"]=$date;
    include("connection.php");

    if($_POST){
        $email=$_POST['useremail'];
        $password=$_POST['userpassword'];
        $error='<label for="promter" class="form-label"></label>';
        $result= $database->query("select * from webuser where email='$email'");
        if($result->num_rows==1){
            $utype=$result->fetch_assoc()['usertype'];
            if ($utype=='p'){
                $checker = $database->query("select * from patient where pemail='$email' and ppassword='$password'");
                if ($checker->num_rows==1){
                    $_SESSION['user']=$email;
                    $_SESSION['usertype']='p';
                    header('location: patient/index.php');
                }else{
                    $error='<label for="promter" class="form-label error-message">Wrong credentials: Invalid email or password</label>';
                }
            }elseif($utype=='a'){
                $checker = $database->query("select * from admin where aemail='$email' and apassword='$password'");
                if ($checker->num_rows==1){
                    $_SESSION['user']=$email;
                    $_SESSION['usertype']='a';
                    header('location: admin/index.php');
                }else{
                    $error='<label for="promter" class="form-label error-message">Wrong credentials: Invalid email or password</label>';
                }
            }elseif($utype=='d'){
                $checker = $database->query("select * from doctor where docemail='$email' and docpassword='$password'");
                if ($checker->num_rows==1){
                    $_SESSION['user']=$email;
                    $_SESSION['usertype']='d';
                    header('location: doctor/index.php');
                }else{
                    $error='<label for="promter" class="form-label error-message">Wrong credentials: Invalid email or password</label>';
                }
            }
        }else{
            $error='<label for="promter" class="form-label error-message">We can\'t find any account for this email.</label>';
        }
    }else{
        $error='<label for="promter" class="form-label">&nbsp;</label>';
    }
    ?>
    <div class="container">
        <p class="header-text">Welcome Back!</p>
        <p class="sub-text">Login with your details to continue</p>
        <div class="form-body">
            <form action="" method="POST">
                <div class="label-td">
                    <label for="useremail" class="form-label">Email:</label>
                </div>
                <div class="label-td">
                    <input type="email" name="useremail" class="input-text" placeholder="Email Address" required>
                </div>
                <div class="label-td">
                    <label for="userpassword" class="form-label">Password:</label>
                </div>
                <div class="label-td">
                    <input type="password" name="userpassword" class="input-text" placeholder="Password" required>
                </div>
                <div>
                    <?php echo $error ?>
                </div>
                <div>
                    <input type="submit" value="Login" class="login-btn">
                </div>
            </form>
            <div>
                <br>
                <label for="" class="sub-text">Don't have an account? </label>
                <a href="signup.php" class="hover-link1">Sign Up</a>
                <br><br><br>
            </div>
        </div>
    </div>
</body>
</html>
