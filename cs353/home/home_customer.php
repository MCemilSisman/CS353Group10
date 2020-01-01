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

// prepare sql statement for repair request
$data0 = "";
$sql0 = "SELECT repair_request_id, product.name, explanation FROM (user, repair_request, product) WHERE customer_id = {$_SESSION['id']} AND user.id = customer_id AND product.id = product_id";
$result = mysqli_query($con, $sql0);
if (mysqli_num_rows($result) > 0) {
    // output data of each row
	$data0 .= "<table><tr><th style=\"text-align:left\" width=\"20%\">Repair Request ID</th>
		<th style=\"text-align:left\" width=\"20%\">Product</th>
		<th style=\"text-align:left\" width=\"40%\">Explanation</th>
		</tr>";
		while($row = mysqli_fetch_assoc($result)) {
			$data0 .= "<tr><td width=\"20%\">" . $row["repair_request_id"].
			"</td><td width=\"20%\">" . $row["name"]. 
			"</td><td width=\"40%\">" . $row["explanation"]. 
			"</td><td width =\"20%\"><a href=\"../show/show_repair_request.php?id=" . $row["repair_request_id"] . "&pname=" . $row["name"] . "&userType=customer" . "\">Show Repair Request</a>" .
			"</td></tr>";
		}
    $data0 .= "</table>";
} 
else {
    $data0 .= "You haven't made any repair requests";
}

// prepare sql statement for complaint
$data1 = "";
$sql1 = "SELECT complaint_id, repair_request_id, customer_id, product.name, status, topic, explanation from (
	(SELECT complaint_id, repair_request_id, customer_id, product_id, status, topic, complaint.explanation from repair_request JOIN complaint using (repair_request_id, customer_id) WHERE customer_id = {$_SESSION['id']}) as k
	, product)
	WHERE product_id = product.id";
$result = mysqli_query($con, $sql1);
if (mysqli_num_rows($result) > 0) {
    // output data of each row
	$data1 .= "<table><tr><th style=\"text-align:left\" width=\"20%\">Complaint ID</th>
		<th style=\"text-align:left\" width=\"20%\">Product</th>
		<th style=\"text-align:left\" width=\"20%\">Status</th>
		<th style=\"text-align:left\" width=\"20%\">Topic</th>
		</tr>";
		while($row = mysqli_fetch_assoc($result)) {
			$data1 .= "<tr><td width=\"20%\">" . $row["complaint_id"].
			"</td><td width=\"20%\">" . $row["name"]. 
			"</td><td width=\"20%\">" . $row["status"]. 
			"</td><td width=\"20%\">" . $row["topic"]. 
			"</td><td width =\"20%\"><a href=\"../show/show_complaint.php?id=" . $row["complaint_id"] . "&pname=" . $row["name"] . "&userType=customer" . "\">Show Complaint</a>" .
			"</td></tr>";
		}
    $data1 .= "</table>";
} 
else {
    $data1 .= "You haven't made any repair requests";
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Customer Main Page</title>
		<link href="../style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Maintenance System</h1>
				<a href="home.php"><i class="fas fa-arrow-alt-circle-left"></i>Back</a>
				<a href="../logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
		<div class="content">
			<h2>Customer Main Page</h2>
            <div>
				<a href="../create/create_repair_request.php">File a Repair Request</a>
				<a href="../create/create_complaint.php">File a Complaint</a>
			</div>
			<div>
                <p><b style="font-size:24px;">Your Repair Requests</b></p>
                <?=$data0?>
			</div>
			<div>
                <p><b style="font-size:24px;">Your Complaints</b></p>
                <?=$data1?>
			</div>
		</div>
	</body>
</html>