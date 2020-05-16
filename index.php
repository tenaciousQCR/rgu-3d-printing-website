<?php
/**
 * Get data from database
 */

// Included ProductObj.php for custom object to hold data from database
include 'ProductObj.php';

// Database information
$host = "160.153.131.196";
$username = "mynamejeff";
$password = "12345";
$dbname = "3dprintrgu";
$productTable = "products";

// Connect to database
$cnx = mysqli_connect($host, $username, $password, $dbname);
// Check connection
if ($cnx->connect_error) {
    die("Failed to connect to database: " . mysqli_connect_error());
}

// SQL query
$query = "SELECT * FROM $productTable";

// Execute the query
$queryResult = mysqli_query($cnx, $query) or die(mysqli_error($cnx));

// Array containing every row of results in form of custom object to contain the data
$results = [];

if (!$queryResult) {
    // Error, show alert and stop loop
    $message = "Server error: Failed to execute query";
    echo "<script type='text/javascript'>alert('$message');</script>";
} else {
    // Get the result and add the database items to array
    while ($row = mysqli_fetch_array($queryResult)) {
        $ProdID = $row["ProductID"];
        $ProdName = $row["ProductName"];
        $ProdPrice = $row["ProductPrice"];
        $ProdImgUrl = $row["ProductImageUrl"];

        $ProdPrice = "£".$ProdPrice;

        $ProductObt = new ProductObj($ProdID, $ProdName, $ProdPrice, $ProdImgUrl);
        array_push($results, $ProductObt);
    }
}

// User login
session_start();

// check if user up or down voted a product
if (isset($_GET['tu']) || isset($_GET['td'])) {
    // user did vote on a product
    if (!isset($_SESSION['loggedin'])) {
        // User not logged in, show error
        $_SESSION['alert'] = "Error: you need to login to do that.";
        header('Location: index.php');
        exit();
    }

    // get the product id
    $thumbProductID = null;
    $vote = null;
    if ($_GET['tu'] != null) {
        $thumbProductID = $_GET['tu'];
        $vote = 1;
    } else {
        $thumbProductID = $_GET['td'];
        $vote = 0;
    }

    $userID = $_SESSION['id'];
    // TableKey = ProductID + UserID -> it ensures it is unique and a user can only vote once
    $tableKey = "$thumbProductID"."$userID";

    // use replace instead of insert to replace it if it exists
    $userRatingQuery = "REPLACE INTO ratings (TableKey, ProductID, UserID, PositiveVote) VALUES (?, ?, ?, ?)";
    if ($stmt = $cnx->prepare($userRatingQuery)) {
        // Bind parameters (s = string, i = int etc
        $stmt->bind_param('iiii', $tableKey, $thumbProductID, $userID, $vote);
        $stmt->execute();
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

<div class="spinner-loader"></div>


<!-- LOGIN/REGISTER MODAL START -->
<div class="modal" id="loginRegisterModal">
    <!-- Modal content -->
    <div class="modal-content">
        <div class="modal-body" id="login-register-modal-form">
            <input type='checkbox' id='form-switch'>
            <form id='login-form' action="login.php" method='post'>
                <div class="modal-header">
                    <h2>Login</h2>
                </div>
                <input type="text" placeholder="Email" name="email" required>
                <input type="password" placeholder="Password" name="password" required>
                <button class="btn" type='submit'>Login</button>
                <label for='form-switch'><span>Register</span></label>
            </form>
            <form id='register-form' action="register.php" method='post'>
                <div class="modal-header">
                    <h2>Register</h2>
                </div>
                <input type="email" placeholder="Email" name="email" required>
                <input type="password" placeholder="Password" name="password" required>
                <input type="password" placeholder="Re Password" name="repassword" required>
                <button class="btn" type='submit'>Register</button>
                <label for='form-switch'>Already Member ? Sign In Now..</label>
            </form>
        </div>
        <div class="modal-footer">
            <div class="col-md-12 account-padding">
                <button id="cancel-lr" class="btn ">Cancel</button>
            </div>
        </div>
    </div>
</div>
<!-- LOGIN/REGISTER MODAL END -->


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
                        <li class="header-link header-active"><a href="index.php">Home</a></li>
                        <li class="header-link"><a href="about.html">About</a></li>
                        <li class="header-link"><a href="contact.html">Contact</a></li>
                        <?php
                        // Show or hide login/register button and profile icon depending on if user is logged in
                        if (isset($_SESSION['loggedin'])) {
                            echo "<li class=\"header-link header-right-align account-inactive\"><a id=\"login\">Login</a>/<a id=\"register\">Register</a></li>";
                            echo "<li class=\"header-link header-right-align\" id=\"profile-icon\"><a class=\"profile_icon\" href=\"account.php\" style=\"padding: 0\"></a></li>";
                        } else {
                            echo "<li class=\"header-link header-right-align\"><a id=\"login\">Login</a>/<a id=\"register\">Register</a></li>";
                            echo "<li class=\"header-link header-right-align account-inactive\" id=\"profile-icon\"><a class=\"profile_icon\" href=\"account.php\" style=\"padding: 0\"></a></li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <!-- HEADER END -->


    <!-- CONTENT START -->
    <div id="fh5co-product" style="min-height: 60%">
        <div class="container" id="product-container">
            <div class="row animate-box">
                <div class="col-md-8 col-md-offset-2 text-center" style="margin-top: 10px; margin-bottom: 25px">
                    <h2>Available Pre-designed Prints</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- element to fill remaining space and stick footer to bottom -->
    <div style="flex-grow : 1;"></div>
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
<!-- Login/register -->
<script src="js/profile.js"></script>
<!-- error modal -->
<script src="js/error-modal.js"></script>

</body>
<?php
// Check if an alert needs to be displayed (after inputting incorrect stuff when trying to login or register)
if(isset($_SESSION['alert']) && strlen($_SESSION['alert']) > 0){
    $message = $_SESSION['alert'];
    echo "<script type='text/javascript'>showModal();</script>";
    echo "<script type='text/javascript'>document.getElementById(\"error-modal-message\").innerHTML = '$message'</script>";
    $_SESSION['alert'] = "";
}
?>

<!-- Create elements populated with data from the database -->
<?php
for ($i = 0; $i != count($results); $i+=3) {
    // Array doesn't have first index, stop looping
    if (count($results) < 1 || $results[$i] == null) {
        $message = "No more products in database! $results[$i]";
        echo "<script type='text/javascript'>console.log('$message');</script>";
        break;
    }

    // Get product data from ProductObj, created at top of file
    $ProductId1 = $results[$i]->ProdID;
    $ProductName1 = $results[$i]->ProdName;
    $ProductPrice1 = $results[$i]->ProdPrice;
    $ProductImgUrl1 = $results[$i]->ProdImgUrl;
    // Get total number of ratings for this product
    $totalRatings1 = mysqli_query($cnx, "SELECT COUNT(*) as `count` FROM ratings WHERE ProductID = " . $ProductId1) or die(mysqli_error($cnx));
    $totalRatingsRow1 = mysqli_fetch_array($totalRatings1);
    $totalRatingCount1 = $totalRatingsRow1['count'];
    // Get number of positive ratings to workout percentage
    $positiveRatings1 = mysqli_query($cnx, "SELECT COUNT(*) as `count` FROM ratings WHERE ProductID = " . $ProductId1 . " AND PositiveVote = 1") or die(mysqli_error($cnx));
    $positiveRatingCount1 = mysqli_fetch_array($positiveRatings1)['count'];
    // Percentage positive
    $percentPositive1 = ($positiveRatingCount1 / $totalRatingCount1) * 100;

    // Thumb style
    $thumbUpStyle1 = "thumb-up";
    $thumbDownStyle1 = "thumb-down";
    // check if the user has voted on product one (if they're logged in)
    if ($_SESSION['loggedin']) {
        $userID = $_SESSION['id'];
        $tableKey1 = "$ProductId1"."$userID";
        $userVotedQry1 = mysqli_query($cnx, "SELECT PositiveVote as `vote` FROM ratings WHERE TableKey = $tableKey1") or die(mysqli_error($cnx));
        if($userVotedQry1->num_rows > 0) {
            // User has voted
            $userVoteP1 = mysqli_fetch_array($userVotedQry1)['vote'];
            if ($userVoteP1 == 1) {
                $thumbUpStyle1 = "thumb-up-active";
            } else {
                $thumbDownStyle1 = "thumb-down-active";
            }
        }
    }

    $ProductId2 = "";
    $ProductName2 = "";
    $ProductPrice2 = "";
    $ProductImgUrl2 = "";

    $ProductId3 = "";
    $ProductName3 = "";
    $ProductPrice3 = "";
    $ProductImgUrl3 = "";

    // Array has 2nd index
    if ($results[$i + 1] != null) {
        $ProductId2 = $results[$i + 1]->ProdID;
        $ProductName2 = $results[$i + 1]->ProdName;
        $ProductPrice2 = $results[$i + 1]->ProdPrice;
        $ProductImgUrl2 = $results[$i + 1]->ProdImgUrl;

        // Get total number of ratings for this product
        $totalRatings2 = mysqli_query($cnx, "SELECT COUNT(*) as `count` FROM ratings WHERE ProductID = " . $ProductId2) or die(mysqli_error($cnx));
        $totalRatingsRow2 = mysqli_fetch_array($totalRatings2);
        $totalRatingCount2 = $totalRatingsRow2['count'];
        // Get number of positive ratings to workout percentage
        $positiveRatings2 = mysqli_query($cnx, "SELECT COUNT(*) as `count` FROM ratings WHERE ProductID = " . $ProductId2 . " AND PositiveVote = 1") or die(mysqli_error($cnx));
        $positiveRatingCount2 = mysqli_fetch_array($positiveRatings2)['count'];
        // Percentage positive
        $percentPositive2 = ($positiveRatingCount2 / $totalRatingCount2) * 100;

        // Thumb style
        $thumbUpStyle2 = "thumb-up";
        $thumbDownStyle2 = "thumb-down";
        // check if the user has voted on product one (if they're logged in)
        if ($_SESSION['loggedin']) {
            $tableKey2 = "$ProductId2"."$userID";
            $userVotedQry2 = mysqli_query($cnx, "SELECT PositiveVote as `vote` FROM ratings WHERE TableKey = $tableKey2") or die(mysqli_error($cnx));
            if($userVotedQry2->num_rows > 0) {
                // User has voted
                $userVoteP2 = mysqli_fetch_array($userVotedQry2)['vote'];
                if ($userVoteP2 == 1) {
                    $thumbUpStyle2 = "thumb-up-active";
                } else {
                    $thumbDownStyle2 = "thumb-down-active";
                }
            }
        }
    }

    // Array has 3rd index
    if ($results[$i + 2] != null) {
        $ProductId3 = $results[$i + 2]->ProdID;
        $ProductName3 = $results[$i + 2]->ProdName;
        $ProductPrice3 = $results[$i + 2]->ProdPrice;
        $ProductImgUrl3 = $results[$i + 2]->ProdImgUrl;

        // Get total number of ratings for this product
        $totalRatings3 = mysqli_query($cnx, "SELECT COUNT(*) as `count` FROM ratings WHERE ProductID = " . $ProductId3) or die(mysqli_error($cnx));
        $totalRatingCount3 = mysqli_fetch_array($totalRatings3)['count'];
        // Get number of positive ratings to workout percentage
        $positiveRatings3 = mysqli_query($cnx, "SELECT COUNT(*) as `count` FROM ratings WHERE ProductID = " . $ProductId3 . " AND PositiveVote = 1") or die(mysqli_error($cnx));
        $positiveRatingsRow3 = mysqli_fetch_array($positiveRatings3);
        $positiveRatingCount3 = $positiveRatingsRow3['count'];
        // Percentage positive
        $percentPositive3 = ($positiveRatingCount3 / $totalRatingCount3) * 100;

        // Thumb style
        $thumbUpStyle3 = "thumb-up";
        $thumbDownStyle3 = "thumb-down";
        if ($_SESSION['loggedin']) {
            $tableKey3 = "$ProductId3"."$userID";
            $userVotedQry3 = mysqli_query($cnx, "SELECT PositiveVote as `vote` FROM ratings WHERE TableKey = $tableKey3") or die(mysqli_error($cnx));
            if($userVotedQry3->num_rows > 0) {
                // User has voted
                $userVoteP3 = mysqli_fetch_array($userVotedQry3)['vote'];
                if ($userVoteP3 == 1) {
                    $thumbUpStyle3 = "thumb-up-active";
                } else {
                    $thumbDownStyle3 = "thumb-down-active";
                }
            }
        }
    }

    // Create product elements populated with data from database
    echo '<script type="text/javascript">',
        // Convert php variable into javascript variables so that they're easier to use
        'var mProdName1 = "' .$ProductName1. '";',
        'var mProdPrice1 = "' .$ProductPrice1. '";',
        'var mProdImgUrl1 = "' .$ProductImgUrl1. '";',
        'var mProdID1 = "' .$ProductId1. '";',
        'var mProdPercent1 = "' .$percentPositive1. '";',
        'var mThumbUpStyle1 = "' .$thumbUpStyle1. '";',
        'var mThumbDownStyle1 = "' .$thumbDownStyle1. '";',

        'var mProdName2 = "' .$ProductName2. '";',
        'var mProdPrice2 = "' .$ProductPrice2. '";',
        'var mProdImgUrl2 = "' .$ProductImgUrl2. '";',
        'var mProdID2 = "' .$ProductId2. '";',
        'var mProdPercent2 = "' .$percentPositive2. '";',
        'var mThumbUpStyle2 = "' .$thumbUpStyle2. '";',
        'var mThumbDownStyle2 = "' .$thumbDownStyle2. '";',

        'var mProdName3 = "' .$ProductName3. '";',
        'var mProdPrice3 = "' .$ProductPrice3. '";',
        'var mProdImgUrl3 = "' .$ProductImgUrl3. '";',
        'var mProdID3 = "' .$ProductId3. '";',
        'var mProdPercent3 = "' .$percentPositive3. '";',
        'var mThumbUpStyle3 = "' .$thumbUpStyle3. '";',
        'var mThumbDownStyle3 = "' .$thumbDownStyle3. '";',

    'var productRow = "<div class=\"row\">" +
            "<div class=\"col-md-4 text-center animate-box\">" +
                "<div class=\"product\">" +
                    "<div class=\"product-grid\" style=\"display: block; background-image:url(\'" + mProdImgUrl1 + "\');\">" +
                        "<div class=\"product-voting\"><a class=\"" + mThumbDownStyle1 + "\" href=\"index.php?td=" + mProdID1 + "\"></a><p>" + mProdPercent1 + "</p><p>%</p><a class=\"" + mThumbUpStyle1 + "\" href=\"index.php?tu=" + mProdID1 + "\"></a></div>" +
                    "</div>" +
                    "<div class=\"desc\">" +
                        "<h3>" + mProdName1 + "</h3>" +
                        "<span class=\"price\">" + mProdPrice1 + "</span>" +
                    "</div>" +
                "</div>" +
            "</div>" +
            "<div class=\"col-md-4 text-center animate-box\">" +
                "<div class=\"product\">" +
                    "<div class=\"product-grid\" style=\"display: block; background-image:url(\'" + mProdImgUrl2 + "\');\">" +
                        "<div class=\"product-voting\"><a class=\"" + mThumbDownStyle2 + "\" href=\"index.php?td=" + mProdID2 + "\"></a><p>" + mProdPercent2 + "</p><p>%</p><a class=\"" + mThumbUpStyle2 + "\" href=\"index.php?tu=" + mProdID2 + "\"></a></div>" +
                    "</div>" +
                    "<div class=\"desc\">" +
                        "<h3>" + mProdName2 + "</h3>" +
                        "<span class=\"price\">" + mProdPrice2 + "</span>" +
                    "</div>" +
                "</div>" +
            "</div>" +
            "<div class=\"col-md-4 text-center animate-box\">" +
                "<div class=\"product\">" +
                    "<div class=\"product-grid\" style=\"display: block; background-image:url(\'" + mProdImgUrl3 + "\');\">" +
                        "<div class=\"product-voting\"><a class=\"" + mThumbDownStyle3 + "\" href=\"index.php?td=" + mProdID3 + "\"></a><p>" + mProdPercent3 + "</p><p>%</p><a class=\"" + mThumbUpStyle3 + "\" href=\"index.php?tu=" + mProdID3 + "\"></a></div>" +
                    "</div>" +
                    "<div class=\"desc\">" +
                        "<h3>" + mProdName3 + "</h3>" +
                        "<span class=\"price\">" + mProdPrice3 + "</span>" +
                    "</div>" +
                "</div>" +
            "</div>" +
        "</div>";',

    'var div = document.createElement(\'div\');',
    'div.innerHTML = productRow;',
    'document.getElementById("product-container").appendChild(div);',
    '</script>';
}
?>
</html>