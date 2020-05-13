
<?php
/**
 * Get data from database
 */

// Database information
$host = "160.153.131.196";
$username = "mynamejeff";
$password = "12345";
$dbname = "3dprintrgu";
$table = "products"; //$_GET['products'];

// Connect to database
$cnx = mysqli_connect($host, $username, $password, $dbname);
// Check connection
if ($cnx->connect_error) {
    die("Failed to connect to database: " . mysqli_connect_error());
}

// SQL query
$query = "SELECT * FROM $table";

// Execute the query
$queryResult = mysqli_query($cnx, $query) or die(mysqli_error($cnx));

// Array containing every row of results in form of custom object to contain the data
$results = [];

if (!$queryResult) {
    // Error, show alert and stop loop
    $message = "Server error: Failed to execute query";
    echo "<script type='text/javascript'>alert('$message');</script>";
} else {
    $message = "great success";
    echo "<script type='text/javascript'>alert('$message');</script>";
    // Get the result and add the database items to array
    while ($row = mysqli_fetch_array($queryResult)) {
        /*$UniqueIdentifier = $row["UniqueIdentifier"];
        $Site = $row["Site"];
        $Metal = $row["Metal"];
        $BullionType = $row["BullionType"];
        $Quantity = $row["Quantity"];
        $Price = $row["Price"];
        $Weight = $row["Weight"];
        $Link = $row["Link"];
        $PriceMin = $row["PriceMin"];
        $WeightInGrams = $row["WeightInGrams"];
        $PricePerGram = $currencySymbol . round($row["PricePerGram"], 2);

        if (strpos($Weight, "_") !== false) {
            // Weight contains _ so we need to remove it
            $Weight = substr($Weight, 0, strpos($Weight, '_'));
        }

        if (strpos($Weight, "tola") !== false) {
            // Weight contains tola, make it a little bit nicer to look at
            $Weight = str_replace("tola", " Tola", $Weight);
        }

        if (strpos($Weight, "x") !== false) {
            // Weight contains x so multiply it to make it look nicer

            // Get the unit
            if (strpos($Weight, "kg") !== false) {
                $unit = "kg";
            } else if (strpos($Weight, "g") !== false) {
                $unit = "g";
            } else if (strpos($Weight, "Tola") !== false) {
                $unit = " Tola";
            } else if (strpos($Weight, "oz") !== false) {
                $unit = "oz";
            }

            // Separate the numbers
            $firstNum = explode("x", $Weight)[0];
            $secondNum = explode("x", $Weight)[1];
            preg_match("/([0-9]+)/", $secondNum, $matches)[0];
            $secondNum = $matches[0];

            // Multiply the numbers and add the unit back in
            $Weight = ($firstNum * $secondNum) + $unit;
        }

        // Maximise the amount of bullion the user can buy
        // Get the number of times the unit can fit into the user's desired spend
        $Quantity = floor($currencyAmount / $Price);
        // Calculate the new total price & weight
        $Price = $currencySymbol . round($Price * $Quantity, 1);
        $WeightInGrams = round($WeightInGrams * $Quantity, 2);

        // Add unit to $WeightInGrams
        $WeightInGrams = $WeightInGrams . "g";

        $BullionObj = new BullionObj($UniqueIdentifier, $Site, $Metal, $BullionType, $Quantity, $Price, $Weight, $Link, $PriceMin, $WeightInGrams, $PricePerGram);
        array_push($results, $BullionObj);*/
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
</head>
<body class="d-flex flex-column">

<div class="spinner-loader"></div>

<div id="page" class="d-flex flex-column flex-grow-1">
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
                        <li class="header-link header-right-align" id="login-register"><a onclick="login();">Login</a>/<a onclick="register();">Register</a></li>
                        <li class="header-link header-right-align account-inactive" id="profile-icon"><a class="profile_icon" href="account.html" style="padding: 0"></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <!-- HEADER END -->


    <!-- CONTENT START -->
    <div id="fh5co-product">
        <div class="container">
            <div class="row animate-box">
                <div class="col-md-8 col-md-offset-2 text-center" style="margin-top: 10px; margin-bottom: 25px">
                    <h2>Available Pre-designed Prints</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 text-center animate-box">
                    <div class="product">
                        <a class="product-grid" style="background-image:url(img/product-1.jpg); display: block"> </a>
                        <div class="desc">
                            <h3><a href="product.php">there is a spider deep in my hole</a></h3>
                            <span class="price">$350</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center animate-box">
                    <div class="product">
                        <a class="product-grid" style="background-image:url(img/product-1.jpg); display: block"> </a>
                        <div class="desc">
                            <h3><a href="product.php">Product skadoosh</a></h3>
                            <span class="price">$600</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center animate-box">
                    <div class="product">
                        <a class="product-grid" style="background-image:url(img/product-1.jpg); display: block"> </a>
                        <div class="desc">
                            <h3><a href="product.php">The fire nation attacked</a></h3>
                            <span class="price">$780</span>
                        </div>
                    </div>
                </div>
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