<?php
// User login
session_start();
if (!isset($_SESSION['loggedin'])) {
    // User not logged in, redirect to index
    header('Location: index.php');
}

// Logout if user clicks logout
if (isset($_GET['logout'])) {
    session_destroy();
    // Redirect to home
    header('Location: index.php');
}

// Database information
$host = "160.153.131.196";
$username = "mynamejeff";
$password = "12345";
$dbname = "3dprintrgu";

// Connect to database
$cnx = mysqli_connect($host, $username, $password, $dbname);
// Check connection
if ($cnx->connect_error) {
    die("Failed to connect to database: " . mysqli_connect_error());
}

// get the users email from session
$email = $_SESSION['name'];
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>3D Print Co</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="3D Print Co Shop Home"/>
    <meta name="author" content="Quentin Robertson 1600961"/>

    <!-- Animate.css -->
    <link rel="stylesheet" href="css/animate.css">

    <!-- Bootstrap  -->
    <link rel="stylesheet" href="css/bootstrap.css">

    <!-- Flexslider  -->
    <link rel="stylesheet" href="css/flexslider.css">

    <!-- Theme style  -->
    <link rel="stylesheet" href="css/main_style.css">
</head>
<body>

<div id="mModal" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <div class="modal-header">
            <span class="close">&times;</span>
            <h2>Change your password</h2>
        </div>
        <div class="modal-body">
            <form>
                <div class="row">
                    <div class="col-md-6 account-padding">
                        <label>Current password: </label>
                        <!--suppress HtmlFormInputWithoutLabel -->
                        <input name="cpass" value="">
                    </div>
                    <div class="col-md-12 account-padding">
                        <label>New password: </label>
                        <!--suppress HtmlFormInputWithoutLabel -->
                        <input name="npass" value="">
                    </div>
                    <div class="col-md-12 account-padding">
                        <label>Confirm new password: </label>
                        <!--suppress HtmlFormInputWithoutLabel -->
                        <input name="npass-conf" value="">
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <div class="col-md-12 account-padding">
                <button class="btn type--uppercase">Cancel</button>
                <button class="btn type--uppercase">Save</button>
            </div>
        </div>
    </div>
</div>

<div class="spinner-loader"></div>

<div id="page">
    <!-- HEADER START -->
    <nav class="header-nav" role="navigation">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-xs-2">
                    <div id="fh5co-logo"><a href="index.php">3D Print Co.</a></div>
                </div>
                <div class="col-md-6 text-center menu-1">
                    <ul>
                        <li class="header-link"><a href="index.php">Home</a></li>
                        <li class="header-link"><a href="about.html">About</a></li>
                        <li class="header-link"><a href="contact.html">Contact</a></li>
                        <li class="header-link header-right-align" id="logout"><a href="account.php?logout=true">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <!-- HEADER END -->


    <!-- CONTENT START -->
    <div class="container" style="min-height: 60%;">
        <div class="row">
            <div class="col-lg-8" style="margin-left: 10%">
                <h3 style="padding-top: 20px">Your Account</h3>
                <form>
                    <div class="row">
                        <div class="col-md-12 account-padding">
                            <label>Email Address:</label>
                            <!--suppress HtmlFormInputWithoutLabel -->
                            <input type="email" name="email" value="<?php echo$email?>">
                        </div>
                        <div class="col-md-6 account-padding">
                            <button id="chng-pass-btn">Change Password</button>
                        </div>
                        <div class="col-md-12 account-padding">
                            <button class="btn type--uppercase">Save Profile</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- CONTENT END -->


    <!-- FOOTER START -->
    <footer id="fh5co-footer" class="mt-auto" role="contentinfo">
        <div class="container">
            <div class="row row-pb-md">
                <div class="col-md-4 fh5co-widget">
                    <h3>3D Print Co.</h3>
                    <p>3D printing done right</p>
                </div>

                <div class="col-md-4 fh5co-widget">
                    <strong>Useful links</strong>
                    <ul class="footer-link">
                        <li><a class="footer-link-color" href="index.php">Home</a></li>
                        <li><a class="footer-link-color" href="about.html">About</a></li>
                        <li><a class="footer-link-color" href="contact.html">Contact</a></li>
                    </ul>
                </div>
            </div>

            <div class="row copyright">
                <div class="text-center">
                    <p>
                        <small class="block">&copy; 2020 3D Print Co. All Rights Reserved.</small>
                        <small class="block">Designed and developed by Quentin Robertson 1600961</small>
                    </p>
                </div>
            </div>
        </div>
    </footer>
    <!-- FOOTER END -->
</div>


<div class="gototop js-top">
    <a href="#" class="js-gotop"><i class="icon-arrow-up"></i></a>
</div>

<!-- account specific js -->
<script src="js/account.js"></script>
<!-- jQuery -->
<script src="js/jquery.min.js"></script>
<!-- jQuery Easing -->
<script src="js/jquery.easing.1.3.js"></script>
<!-- Bootstrap -->
<script src="js/bootstrap.min.js"></script>
<!-- Waypoints -->
<script src="js/jquery.waypoints.min.js"></script>
<!-- Flexslider -->
<script src="js/jquery.flexslider-min.js"></script>
<!-- Main -->
<script src="js/main.js"></script>

</body>
</html>