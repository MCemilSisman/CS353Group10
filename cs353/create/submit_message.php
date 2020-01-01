<?php
include '../config.php';
session_start();
$errors = array();
// connect using credentials
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) { //connection error
	die ('Cannot connect to the database: ' . mysqli_connect_error());
}

$userType = "";
$conversation_no = "";
$latest_conversation_no = "";
$complaint_id = "";
if (isset($_GET['userType'])) {
    $userType = $_GET['userType'];
    $conversation_no = $_GET['conversation_no'];
    $complaint_id = $_GET['complaint_id'];;
}

if (isset($_POST['submit'])) {
    // receive all input values from the form
    $message = mysqli_real_escape_string($con, $_POST['message']);
    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error into $errors array
    if (empty($message)) { array_push($errors, "Message is a required field"); }
}

// get the latest conversation
$sql0 = "SELECT conversation_no FROM conversation WHERE complaint_id = $complaint_id";
$result0 = mysqli_query($con, $sql0);
if (mysqli_num_rows($result0) > 0) {
    while($row0 = mysqli_fetch_assoc($result0)) {
        $latest_conversation_no = $row0["conversation_no"];
    }
}

$query = "";
if($latest_conversation_no == $conversation_no && $userType == "customer_service" ){
    //update
    $query = "UPDATE conversation c
                SET c.customer_service_msg = '$message', c.customer_service_msg_date = '03/01/2020'
                WHERE c.complaint_id = $complaint_id AND c.conversation_no = $conversation_no";
}
else if($latest_conversation_no != $conversation_no && $userType == "customer_service" ){
    //error
    array_push($errors, "You must wait for the customer's comment first");
}
else if($latest_conversation_no == $conversation_no && $userType == "customer"){
    //error
    array_push($errors, "You must wait for a reply first");
}
else if($latest_conversation_no != $conversation_no && $userType == "customer"){ 
    //insert
    $query = "INSERT INTO conversation (complaint_id, conversation_no, customer_id, customer_service_id, customer_msg, customer_msg_date, customer_service_msg, customer_service_msg_date) 
                VALUES( $complaint_id, $conversation_no, {$_SESSION['id']}, 3001, '$message', '03/01/2020', 'null', 'null' )";
}

// Insert conversation
if (count($errors) == 0) {
    mysqli_query($con, $query);
    header("Location: ../home/home_". $userType . ".php?Message=" . urlencode("Your message has been sent"));
}
else { // display errors if any
    $alertMsg = array_pop($errors);
    foreach( $errors as $counter ){
        $alertMsg .= ", " . array_pop($errors);
    }
    header("Location: ../home/home_". $userType . ".php?Message=" . urlencode($alertMsg));
}
?>