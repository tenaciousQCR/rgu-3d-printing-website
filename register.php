<?php
session_start();
// Change this to your connection info.
$DATABASE_HOST = "160.153.131.196";
$DATABASE_USER = "mynamejeff";
$DATABASE_PASS = "12345";
$DATABASE_NAME = "3dprintrgu";

// Try and connect using the info above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    // If there is an error with the connection, stop the script and display the error.
    exit('Failed to connect to database: ' . mysqli_connect_error());
}

// Validate the users inpuit
if (!isset($_POST['email'], $_POST['password'], $_POST['repassword'])) {
    exit('Please fill both the email and password fields.');
} else if ($_POST['password'] != $_POST['repassword']) {
    exit('Passwords do not match.');
}

// Check if email is already registered
// prepare query before execution to avoid sql injection
if ($stmt = $con->prepare('SELECT email FROM users WHERE email = ?')) {
    // Bind parameters (s = string, i = int etc
    $stmt->bind_param('s', $_POST['email']);
    $stmt->execute();
    // Store the result to check if it exists in database
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Email already exist
        exit('Email is already registered.');
    }
    // email doesnt exist, proceed!
    $stmt->close();
}

if ($stmt = $con->prepare('INSERT INTO users (id, email, password) VALUES (DEFAULT, ?, ?)')) {
    // hash the users password
    $password = password_hash($_POST['password'], PASSWORD_ARGON2I);

    // Bind parameters (s = string, i = int etc
    $stmt->bind_param('ss', $_POST['email'], $password);
    $stmt->execute();

    // get the id that was auto incremented
    $id = mysqli_insert_id($con);

    // log the user in
    session_regenerate_id();
    $_SESSION['loggedin'] = TRUE;
    $_SESSION['name'] = $_POST['email'];
    $_SESSION['id'] = $id;

    header('Location: index.php');
}
?>
