<?php
include 'config.php';
session_start();

// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit();
}

// connect using credentials
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	die ('Cannot connect to the database: ' . mysqli_connect_error());
}

// message to display if it's coming back
if (isset($_GET['Message'])) {
    print '<script type="text/javascript">alert("' . $_GET['Message'] . '");</script>';
}

//get cid and position
$cid = "";
$position = "";
if (isset($_GET['cid']) && isset($_GET['position']) ) {
	$cid = $_GET['cid'];
	$position = $_GET['position'];
}

// prepare sql stament
$sql = "SELECT cid FROM apply WHERE cid = '{$cid}' AND position = '{$position}' AND eid = '{$_SESSION['id']}'";
$result = mysqli_query($con, $sql);
if (mysqli_num_rows($result) > 0) {
	header("Location: home/home.php?Message=" . urlencode("You have already applied to this company"));
}
else{
	$sql = "SELECT cid, cname FROM company WHERE cid = '{$cid}' AND position = '{$position}' AND
		quota = (SELECT COUNT(eid) FROM apply WHERE cid = '{$cid}' AND position = '{$position}' )";
	$result = mysqli_query($con, $sql);
	if (mysqli_num_rows($result) > 0) {
		header("Location: home/home.php?Message=" . urlencode("The maximum application quota for this company has been reached"));
	}
	else{
		$insertSQL = "INSERT INTO apply (eid,cid,position) VALUES ({$_SESSION['id']},'{$cid}','{$position}');";
        if(mysqli_query($con, $insertSQL)){
            header("Location: home/home.php?Message=" . urlencode("New application successful"));
        }
        else{
            header("Location: home/home.php?Message=" . urlencode("Application NOT successful"));
        }
	}
}
?>