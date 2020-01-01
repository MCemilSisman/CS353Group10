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
    $repair_request_id = mysqli_real_escape_string($con, $_POST['repair_request_id']);
    $spare_part_name = mysqli_real_escape_string($con, $_POST['spare_part_name']);
    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error into $errors array
    if (empty($repair_request_id)) { array_push($errors, "Repair Request ID is a required field"); }
    if (empty($spare_part_name)) { array_push($errors, "Spare Part Name is a required field"); }
}

$spare_part_id = 0;

// confirming the repair_request_id
$sql0 = "SELECT repair_request_id FROM repair_request WHERE repair_request_id = $repair_request_id AND tech_staff_id = {$_SESSION['id']}";
$result0 = mysqli_query($con, $sql0);
if (mysqli_num_rows($result0) <= 0) {
    array_push($errors, "You are not responsible for this repair request");
}
else{
    // check if a request has already been made
    $sql2 = "SELECT spare_part_id FROM request WHERE repair_request_id = $repair_request_id AND tech_staff_id = {$_SESSION['id']}";
    $result2 = mysqli_query($con, $sql2);
    if (mysqli_num_rows($result2) > 0) {
        array_push($errors, "You already requested a spare part for this repair");
    }
}

// get spare part id
$sql1 = "SELECT id FROM spare_part WHERE name = '$spare_part_name'";
$result1 = mysqli_query($con, $sql1);
if (mysqli_num_rows($result1) > 0) {
    $row1 = mysqli_fetch_assoc($result1);
    $spare_part_id = $row1["id"];
}
else{
    array_push($errors, "This spare part does not exist");
}

// Insert spare part request
if (count($errors) == 0) {
    $query = "INSERT INTO request (repair_request_id, tech_staff_id, spare_part_id) 
                VALUES( $repair_request_id, {$_SESSION['id']}, $spare_part_id)";
    mysqli_query($con, $query);
    header("Location: ../home/home_tech_staff.php?Message=" . urlencode("Your spare part request has been submitted"));
}
else { // display errors if any
    $alertMsg = array_pop($errors);
    foreach( $errors as $counter ){
        $alertMsg .= ", " . array_pop($errors);
    }
    header("Location: ../home/home_tech_staff.php?Message=" . urlencode($alertMsg));
}
?>