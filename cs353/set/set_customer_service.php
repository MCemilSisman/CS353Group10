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
    $salary = mysqli_real_escape_string($con, $_POST['salary']);
    $work_hours = mysqli_real_escape_string($con, $_POST['work_hours']);
    $position = mysqli_real_escape_string($con, $_POST['position']);
    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error into $errors array
    if (empty($position)) { array_push($errors, "Position is a required field"); }
    if (empty($work_hours)) { array_push($errors, "Work Hours is a required field"); }
    if (empty($salary)) { array_push($errors, "Salary is a required field"); }
}

// Insert employee and customer service
if (count($errors) == 0) {
    $query0 = "INSERT INTO employee (id, salary, work_hours) 
                VALUES( {$_SESSION['id']}, '$salary', '$work_hours' )";
    mysqli_query($con, $query0);
    $query1 = "INSERT INTO customer_service (id, position) 
                VALUES( {$_SESSION['id']}, '$position')";
    mysqli_query($con, $query1);
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