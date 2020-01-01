<?php
include 'config.php';
session_start();
$errors = array();
// connect using credentials
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) { //connection error
	die ('Cannot connect to the database: ' . mysqli_connect_error());
}

if (isset($_POST['back'])) {
    header('location: index.html');
}
else if (isset($_POST['signedUser'])) {
    // receive all input values from the form
    $username = mysqli_real_escape_string($con, $_POST['name']);
    $adress = mysqli_real_escape_string($con, $_POST['adress']);
    $phone_number = mysqli_real_escape_string($con, $_POST['phone_number']);
    $userType = "";
    $password = mysqli_real_escape_string($con, $_POST['password']);
    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error into $errors array
    if (!isset($_POST['user'])) { array_push($errors, "Please select a user type"); }
    else{ $userType = mysqli_real_escape_string($con, $_POST['user']); }
    if (empty($phone_number)) { array_push($errors, "Phone Number is a required field"); }
    if (empty($adress)) { array_push($errors, "Adress is a required field"); }
    if (empty($password)) { array_push($errors, "Password is a required field"); }
    if (empty($username)) { array_push($errors, "Username is a required field"); }
}

// check the database to make sure a user of the same type does not already exist with the same username
if(!empty($userType)){
    $user_check_query = "SELECT name FROM user WHERE name='$username' AND type = $userType";
    $result = mysqli_query($con, $user_check_query);
    if ( mysqli_num_rows($result) != 0 ) { // if user exists
        array_push($errors, "An account with the same user type and username already exists");
    }
}

// Finally, register user if there are no errors in the form
if (count($errors) == 0) {
    $query = "INSERT INTO user (name, password, adress, phone_number, type) 
                VALUES('$username', '$password', '$adress', '$phone_number', $userType)";
    mysqli_query($con, $query);
	alert("Your account has been created");
}
else { // display errors if any
    $alertMsg = array_pop($errors);
    foreach( $errors as $counter ){
        $alertMsg .= ", " . array_pop($errors);
    }
    alert($alertMsg);
}

function alert($msg) {
    echo "<script type='text/javascript'>
        alert('$msg');
        window.location.href='index.html';
        </script>";
}
?>