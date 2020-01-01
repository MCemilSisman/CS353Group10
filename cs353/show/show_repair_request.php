<?php
include '../config.php';
session_start();

// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: ../index.html');
	exit();
}

// connect using credentials
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	die ('Cannot connect to the database: ' . mysqli_connect_error());
}

// message to display if redirected
if (isset($_GET['Message'])) {
    print '<script type="text/javascript">alert("' . $_GET['Message'] . '");</script>';
}

//get repair_request_id
$repair_request_id = "";
$product_name = "";
$userType = "";
if (isset($_GET['id'])) {
    $repair_request_id = $_GET['id'];
    $product_name = $_GET['pname'];
    $userType = $_GET['userType'];;
}

// prepare sql statement for repair request
$preliminary_report = "Preliminary report not submitted yet";
$detailed_report = "Detailed report not submitted yet";
$decision = "A decision has not been made yet";
$sql0 = "SELECT decision, preliminary_report, detailed_report FROM (repair_request NATURAL JOIN repair) WHERE repair_request_id = $repair_request_id";
$result = mysqli_query($con, $sql0);
if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        $decision = $row["decision"];
        $preliminary_report = $row["preliminary_report"];
        if($row["detailed_report"] != "null")
            $detailed_report = $row["detailed_report"];
    }
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Repair Request</title>
		<link href="../style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Maintenance System</h1>
				<a href="../home/home_<?=$userType?>.php"><i class="fas fa-arrow-alt-circle-left"></i>Back</a>
				<a href="../logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
		<div class="content">
			<h2><?=$repair_request_id?> / <?=$product_name?></h2>
			<div>
                <p><b style="font-size:24px;">Preliminary Report</b></p>
                <?=$preliminary_report?>
			</div>
			<div>
                <p><b style="font-size:24px;">Detailed Report</b></p>
                <?=$detailed_report?>
            </div>
            <div>
                <p><b style="font-size:24px;">Decision</b></p>
                <?=$decision?>
			</div>
		</div>
	</body>
</html>