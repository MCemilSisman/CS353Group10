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
    $username = mysqli_real_escape_string($con, $_POST['name']);
    $adress = mysqli_real_escape_string($con, $_POST['adress']);
    $phone_number = mysqli_real_escape_string($con, $_POST['phone_number']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $salary = mysqli_real_escape_string($con, $_POST['salary']);
    $work_hours = mysqli_real_escape_string($con, $_POST['work_hours']);
    $expertise = mysqli_real_escape_string($con, $_POST['expertise']);
    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error into $errors array
    if (!isset($_GET['not_set']) && empty($expertise)) { array_push($errors, "Expertise is a required field"); }
    if (!isset($_GET['not_set']) && empty($work_hours)) { array_push($errors, "Work Hours is a required field"); }
    if (!isset($_GET['not_set']) && empty($salary)) { array_push($errors, "Salary is a required field"); }
    if (empty($phone_number)) { array_push($errors, "Phone Number is a required field"); }
    if (empty($adress)) { array_push($errors, "Adress is a required field"); }
    if (empty($password)) { array_push($errors, "Password is a required field"); }
    if (empty($username)) { array_push($errors, "Username is a required field"); }
}

// check the database to make sure a user of the same type does not already exist with the same username
$user_check_query = "SELECT name FROM user WHERE name='$username' AND type = {$_SESSION['type']}";
$result = mysqli_query($con, $user_check_query);
if ( mysqli_num_rows($result) != 0 ) { // if user exists
    array_push($errors, "An account with the same user type and username already exists");
}

// Update employee, tech_staff and user
if (count($errors) == 0) {
    $query0 = "UPDATE user u
                SET u.name = '$username', u.password = '$password', u.adress = '$adress', u.phone_number = '$phone_number' 
                WHERE u.id = {$_SESSION['id']}";
    mysqli_query($con, $query0);
    $query1 = "UPDATE employee u
                SET u.salary = '$salary', u.work_hours = '$work_hours'
                WHERE u.id = {$_SESSION['id']}";
    mysqli_query($con, $query1);
    $query2 = "UPDATE tech_staff u
                SET u.expertise = '$expertise'
                WHERE u.id = {$_SESSION['id']}";
    mysqli_query($con, $query2);
    session_regenerate_id();
    $_SESSION['name'] = $username;
    $_SESSION['password'] = $password;
    $_SESSION['adress'] = $adress;
    $_SESSION['phone_number'] = $phone_number;
    header("Location: ../home/home.php?Message=" . urlencode("Your information has been changed"));
}
else { // display errors if any
    $alertMsg = array_pop($errors);
    foreach( $errors as $counter ){
        $alertMsg .= ", " . array_pop($errors);
    }
    header("Location: ../home/home.php?Message=" . urlencode($alertMsg));
}
?>