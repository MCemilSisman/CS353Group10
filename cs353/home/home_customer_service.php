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
$sql0 = "SELECT c.name as cname, comp.complaint_id as complaint_id, comp.repair_request_id as repair_request_id, product.name as pname, topic, comp.explanation as explanation, rep.status as status
FROM (user c, user cs, complaint comp, product, repair_request rep) 
WHERE customer_service_id = {$_SESSION['id']} AND cs.id = customer_service_id AND product.id = product_id AND c.id = comp.customer_id AND rep.repair_request_id = comp.repair_request_id";
$result = mysqli_query($con, $sql0);
if (mysqli_num_rows($result) > 0) {
    // output data of each row
	$data0 .= "<table><tr><th style=\"text-align:left\" width=\"20%\">Repair Request ID</th>
		<th style=\"text-align:left\" width=\"15%\">Customer</th>	
		<th style=\"text-align:left\" width=\"15%\">Product</th>
		<th style=\"text-align:left\" width=\"35%\">Topic</th>
		<th style=\"text-align:left\" width=\"15%\">Status</th>
		</tr>";
		while($row = mysqli_fetch_assoc($result)) {
			$data0 .= "<tr><td width=\"20%\">" . $row["complaint_id"].
			"</td><td width=\"15%\">" . $row["cname"]. 
			"</td><td width=\"15%\">" . $row["pname"]. 
			"</td><td width=\"35%\">" . $row["topic"]. 
			"</td><td width=\"15%\">" . $row["status"]. 
			"</td><td width =\"20%\"><a href=\"../show/show_complaint.php?id=" . $row["complaint_id"] . "&pname=" . $row["pname"] . "&userType=customer_service" . "\">Show Complaint</a>" .
			"</td></tr>";
		}
    $data0 .= "</table>";
} 
else {
    $data0 .= "You have no complaints";
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Customer Service Main Page</title>
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
			<h2>Customer Service Main Page</h2>
			<div>
                <p><b style="font-size:24px;">Complaints</b></p>
                <?=$data0?>
			</div>
		</div>
	</body>
</html>