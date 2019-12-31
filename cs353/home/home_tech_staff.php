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

// prepare sql statement
$data = "";
$sql = "SELECT repair_request_id, product.name, explanation FROM (user, repair_request, product) WHERE customer_id = {$_SESSION['id']} AND user.id = customer_id AND product.id = product_id";
$result = mysqli_query($con, $sql);
if (mysqli_num_rows($result) > 0) {
    // output data of each row
	$data .= "<table><tr><th style=\"text-align:left\" width=\"35%\">Repair Request ID</th>
		<th style=\"text-align:left\" width=\"35%\">Product</th>
		<th style=\"text-align:left\" width=\"50%\">Explanation</th>
		</tr>";
		while($row = mysqli_fetch_assoc($result)) {
			$data .= "<tr><td width=\"35%\">" . $row["repair_request_id"].
			"</td><td width=\"35%\">" . $row["name"]. 
			"</td><td width=\"50%\">" . $row["explanation"]. 
			"</td></tr>";
		}
    $data .= "</table>";
} 
else {
    $data .= "You haven't made any repair requests";
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
				<a href="home.php"><i class="fas fa-home"></i>Home</a>
				<a href="user_info.php"><i class="fas fa-user"></i>Change User Info</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
		<div class="content">
			<h2>Customer Main Page</h2>
            <div>
                <p><b style="font-size:24px;">Your applications</b></p>
                <?=$data?>
			</div>
		</div>
	</body>
</html>