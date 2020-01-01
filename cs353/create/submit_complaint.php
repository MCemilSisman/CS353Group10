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
    $topic = mysqli_real_escape_string($con, $_POST['topic']);
    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error into $errors array
    if (empty($explanation)) { array_push($errors, "Explanation is a required field"); }
    if (empty($topic)) { array_push($errors, "Topic is a required field"); }
    if (empty($product_name)) { array_push($errors, "Product name is a required field"); }
}

//confirming the product
$sql0 = "SELECT product_id FROM buys, product WHERE product_id = id AND customer_id = {$_SESSION['id']} AND name = '$product_name'";
$result0 = mysqli_query($con, $sql0);
if (mysqli_num_rows($result0) <= 0) {
    array_push($errors, "You don't own this product");
}
else{
    $row0 = mysqli_fetch_assoc($result0);
    $product_id = $row0["product_id"];
    // finding repair request related to this customer and product
    $sql1 = "SELECT repair_request_id FROM repair_request WHERE product_id = $product_id AND customer_id = {$_SESSION['id']}";
    $result1 = mysqli_query($con, $sql1);
    if (mysqli_num_rows($result1) <= 0) {
        array_push($errors, "You first need to create a repair request");
    }
    else{
        $row1 = mysqli_fetch_assoc($result1);
        $repair_request_id = $row1["repair_request_id"];
    }
}

// Insert complaint
if (count($errors) == 0) {
    $query = "INSERT INTO complaint (repair_request_id, customer_id, customer_service_id, topic, explanation) 
                VALUES( $repair_request_id, {$_SESSION['id']}, 3001, '$topic', '$explanation')";
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