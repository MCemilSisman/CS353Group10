<?php
include '../config.php';
session_start();
$errors = array();
// connect using credentials
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) { //connection error
	die ('Cannot connect to the database: ' . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    // receive all input values from the form
    $birthday = mysqli_real_escape_string($con, $_POST['birthday']);
    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error into $errors array
    if (empty($birthday)) { array_push($errors, "Birthday is a required field"); }
}

// Insert customer
if (count($errors) == 0) {
    $query = "INSERT INTO customer (id, birthday) 
                VALUES( {$_SESSION['id']}, '$birthday')";
    mysqli_query($con, $query);
    header("Location: ../home/home.php?Message=" . urlencode("Your information has been set"));
}
else { // display errors if any
    $alertMsg = array_pop($errors);
    foreach( $errors as $counter ){
        $alertMsg .= ", " . array_pop($errors);
    }
    header("Location: ../home/home.php?Message=" . urlencode($alertMsg));
}
?>