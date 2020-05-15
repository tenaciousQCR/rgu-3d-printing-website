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

// check if the data exists
if ( !isset($_POST['email'], $_POST['password']) ) {
    // data was not sent when it should have been
    exit('Please fill both the email and password fields.');
}

// preparing the SQL statement will prevent SQL injection.
if ($stmt = $con->prepare('SELECT id, password FROM users WHERE email = ?')) {
    // Bind parameters (s = string, i = int, b = blob, etc)
    $stmt->bind_param('s', $_POST['email']);
    $stmt->execute();
    // Store the result to check if the user exists in database later
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password);
        $stmt->fetch();
        // user exists, verify the password
        // passwords are hashed, check the hash not the password
        if (password_verify($_POST['password'], $password)) {
            // Verification success! User has logged in!
            // Create sessions so we know the user is logged in, they basically act like cookies but remember the data on the server.
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $_POST['email'];
            $_SESSION['id'] = $id;

            header('Location: index.php');
        } else {
            echo 'Incorrect password!';
        }
    } else {
        echo 'Invalid email address!';
    }


    $stmt->close();
}
?>