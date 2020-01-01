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
    $product_name = mysqli_real_escape_string($con, $_POST['pname']);
    $explanation = mysqli_real_escape_string($con, $_POST['explanation']);
    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error into $errors array
    if (empty($product_name)) { array_push($errors, "Product name is a required field"); }
    if (empty($explanation)) { array_push($errors, "Explanation is a required field"); }
}

//confirming the product
$sql = "SELECT product_id FROM buys, product WHERE product_id = id AND customer_id = {$_SESSION['id']} AND name = '$product_name'";
$result = mysqli_query($con, $sql);
if (mysqli_num_rows($result) <= 0) {
    array_push($errors, "You don't own this product");
}
else{
    $row = mysqli_fetch_assoc($result);
    $product_id = $row["product_id"];
}

// Insert repair request
if (count($errors) == 0) {
    $query = "INSERT INTO repair_request (customer_id, tech_staff_id, product_id, status, explanation) 
                VALUES( {$_SESSION['id']}, 2003, $product_id, 'Waiting', '$explanation')";
    mysqli_query($con, $query);
    header("Location: ../home/home_customer.php?Message=" . urlencode("Your repair request has been submitted"));
}
else { // display errors if any
    $alertMsg = array_pop($errors);
    foreach( $errors as $counter ){
        $alertMsg .= ", " . array_pop($errors);
    }
    header("Location: ../home/home_customer.php?Message=" . urlencode($alertMsg));
}
?>