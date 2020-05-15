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

// user clicked save
if (isset($_POST['email'])) {
    // only update email if user entered a different one
    if ($_SESSION['name'] != $_POST['email']) {
        if ($stmt = $cnx->prepare('UPDATE users SET email=? WHERE id=?')) {
            // Bind parameters (s = string, i = int etc
            $stmt->bind_param('si', $_POST['email'], $_SESSION["id"]);
            $stmt->execute();

            // Set alert text to be displayed on redirect
            $_SESSION['alert'] = "Email changed successfully.";
            // update session email
            $_SESSION['name'] = $_POST['email'];

            // refresh the page to show the change
            header("Refresh:0");
            exit();
        }
    }
}

// user changed password
if (isset($_POST['cpass'])) {
    // check new password matches
    if ($_POST['npass'] !== $_POST['npass-conf']) {
        $_SESSION['alert'] = "Error: passwords do not match.";
        header("Refresh:0");
        exit();
    }

    // check if their input current password is correct
    if ($stmt = $cnx->prepare('SELECT password FROM users WHERE id = ?')) {
        // Bind parameters (s = string, i = int, b = blob, etc)
        $stmt->bind_param('s', $_SESSION['id']);
        $stmt->execute();
        // Store the result to check if the user exists in database later
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($password);
            $stmt->fetch();

            // user exists, verify the password
            // passwords are hashed, check the hash not the password
            if (password_verify($_POST['cpass'], $password)) {
                // Verification success, now change the password
                if ($stmt = $cnx->prepare('UPDATE users SET password=? WHERE id=?')) {
                    // Bind parameters (s = string, i = int etc
                    $newPassword = password_hash($_POST['npass'], PASSWORD_ARGON2I);
                    $stmt->bind_param('si', $newPassword, $_SESSION["id"]);
                    $stmt->execute();

                    // Set alert text to be displayed on redirect
                    $_SESSION['alert'] = "Password updated.";

                    // refresh the page to show the change
                    header("Refresh:0");
                    exit();
                }
            } else {
                $_SESSION['alert'] = "Error: incorrect password.";
                header("Refresh:0");
                exit();
            }
        } else {
            $_SESSION['alert'] = "Error finding account in database. Please contact support.";
            header("Refresh:0");
            exit();
        }


        $stmt->close();
    }
}
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

    <!-- favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="./apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="./favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./favicon-16x16.png">
    <link rel="manifest" href="./site.webmanifest">
    <link rel="mask-icon" href="./safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#2b5797">
    <meta name="theme-color" content="#ffffff">
</head>
<body>

<!-- PASSWORD MODAL START -->
<div id="mModal" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <div class="modal-header">
            <span class="close">&times;</span>
            <h2>Change your password</h2>
        </div>
        <div class="modal-body">
            <form method="post" id="change-pass-form">
                <div class="row">
                    <div class="col-md-12 account-padding">
                        <label>Current password: </label>
                        <!--suppress HtmlFormInputWithoutLabel -->
                        <input type="password" name="cpass" value="" required>
                    </div>
                    <div class="col-md-12 account-padding">
                        <label>New password: </label>
                        <!--suppress HtmlFormInputWithoutLabel -->
                        <input type="password" name="npass" value="" required>
                    </div>
                    <div class="col-md-12 account-padding">
                        <label>Confirm new password: </label>
                        <!--suppress HtmlFormInputWithoutLabel -->
                        <input type="password" name="npass-conf" value="" required>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <div class="col-md-12 account-padding">
                <button class="btn" id="cancel-change-pass">Cancel</button>
                <button class="btn" type="submit" form="change-pass-form">Save</button>
            </div>
        </div>
    </div>
</div>
<!-- PASSWORD MODAL END -->

<!-- ERROR MODAL START -->
<div class="modal" id="error-modal">
    <div class="modal-content" style="max-width: 500px">
        <div class="modal-body">
            <h3 id="error-modal-message">Placeholder error text.</h3>
        </div>
        <div class="modal-footer">
            <div class="col-md-12 account-padding">
                <button id="error-ok" class="btn ">OK</button>
            </div>
        </div>
    </div>
</div>
<!-- ERROR MODAL END -->

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
                <form method="post">
                    <div class="row">
                        <div class="col-md-12 account-padding">
                            <label>Email Address:</label>
                            <!--suppress HtmlFormInputWithoutLabel -->
                            <input type="email" name="email" value="<?php echo$_SESSION['name']?>">
                        </div>
                        <div class="col-md-6 account-padding">
                            <button id="chng-pass-btn" class="btn">Change Password</button>
                        </div>
                        <div class="col-md-12 account-padding">
                            <button class="btn" type="submit">Save Profile</button>
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
<!-- error modal -->
<script src="js/error-modal.js"></script>

</body>

<?php
// Check if an alert needs to be displayed (after email or password is changed)
if(isset($_SESSION['alert']) && strlen($_SESSION['alert']) > 0){
    $message = $_SESSION['alert'];
    echo "<script type='text/javascript'>showModal();</script>";
    echo "<script type='text/javascript'>document.getElementById(\"error-modal-message\").innerHTML = '$message'</script>";
    $_SESSION['alert'] = "";
}
?>
</html>