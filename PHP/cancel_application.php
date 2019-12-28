<?php
include 'config.php';
session_start();

// connect using credentials
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) { //connection error
	die ('Cannot connect to the database: ' . mysqli_connect_error());
}

//get cid
$cid = "";
if (isset($_GET['cid'])) {
    $cid = $_GET['cid'];
}

$deleteSQL = "DELETE FROM apply WHERE sid = '{$_SESSION['id']}' AND cid = '$cid'";
if(mysqli_query($con, $deleteSQL))
{
    header("Location: home.php?Message=" . urlencode("Cancellation successful"));
}
else {
    header("Location: apply.php?Message=" . urlencode("Cancellation NOT successful"));
}
?>