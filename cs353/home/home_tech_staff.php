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
$sql0 = "SELECT c.name as cname, repair_request_id, product.name as pname, explanation, decision 
FROM (user c, user t, repair_request, product) 
WHERE tech_staff_id = {$_SESSION['id']} AND t.id = tech_staff_id AND product.id = product_id AND c.id = repair_request.customer_id";
$result0 = mysqli_query($con, $sql0);
if (mysqli_num_rows($result0) > 0) {
    // output data of each row
	$data0 .= "<table><tr><th style=\"text-align:left\" width=\"20%\">Repair Request ID</th>
		<th style=\"text-align:left\" width=\"15%\">Customer</th>	
		<th style=\"text-align:left\" width=\"15%\">Product</th>
		<th style=\"text-align:left\" width=\"15%\">Explanation</th>
		<th style=\"text-align:left\" width=\"15%\">Decision</th>
		</tr>";
		while($row0 = mysqli_fetch_assoc($result0)) {
			$data0 .= "<tr><td width=\"20%\">" . $row0["repair_request_id"].
			"</td><td width=\"15%\">" . $row0["cname"]. 
			"</td><td width=\"15%\">" . $row0["pname"]. 
			"</td><td width=\"15%\">" . $row0["explanation"].
			"</td><td width=\"15%\">"; 
			if($row0["decision"] != "")
				$data0 .= $row0["decision"];
			else
				$data0 .= "Waiting for preliminary report submission to make a decision";
			$data0 .= "</td><td width =\"20%\"><a href=\"../show/show_repair_request.php?id=" . $row0["repair_request_id"] . "&pname=" . $row0["pname"] . "&userType=tech_staff" . "\">Show Repair Request</a>" .
			"</td></tr>";
		}
    $data0 .= "</table>";
} 
else {
    $data0 .= "You have no repair requests";
}

// prepare sql statement for spare part
$data1 = "";
$sql1 = "SELECT repair_request_id, name, quantity FROM request NATURAL JOIN spare_part WHERE tech_staff_id = {$_SESSION['id']} AND spare_part_id = id";
$result1 = mysqli_query($con, $sql1);
if (mysqli_num_rows($result1) > 0) {
    // output data of each row
	$data1 .= "<table><tr><th style=\"text-align:left\" width=\"34%\">Repair Request ID</th>
		<th style=\"text-align:left\" width=\"33%\">Spare Part Name</th>	
		<th style=\"text-align:left\" width=\"33%\">Available Quantity</th>
		</tr>";
		while($row1 = mysqli_fetch_assoc($result1)) {
			$data1 .= "<tr><td width=\"34%\">" . $row1["repair_request_id"].
			"</td><td width=\"33%\">" . $row1["name"]. 
			"</td><td width=\"33%\">" . $row1["quantity"]. 
			"</td></tr>";
		}
    $data1 .= "</table>";
} 
else {
    $data1 .= "You have no spare part requests";
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Tech Staff Main Page</title>
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
			<h2>Tech Staff Main Page</h2>
			<div>
                <p><b style="font-size:24px;">Your Repair Requests</b></p>
                <?=$data0?>
			</div>
			<div>
                <p><b style="font-size:24px;">Your Spare Part Requests</b></p>
                <?=$data1?>
			</div>
			<div>
				<a href="../create/create_spare_part_request.php">Make a Spare Part Request</a>
			</div>
		</div>
	</body>
</html>