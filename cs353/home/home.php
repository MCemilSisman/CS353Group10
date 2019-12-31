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

$data0 = "";
$home_user_type = "<a href=\"";
if ( $_SESSION['type'] == 1 ) {
	$userType = "customer";
	$home_user_type .= "home_customer.php";
}
else if ( $_SESSION['type'] == 2 || $_SESSION['type'] == 3 ) { //display info from employee table
	$data0 .= "<div><p><b style=\"font-size:24px;\">Employee Information</b></p>";
	if($_SESSION['type'] == 2){
		$userType = "tech_staff";
		$home_user_type .= "home_tech_staff.php";
	}
	else{
		$userType = "customer_service";
		$home_user_type .= "home_customer_service.php";
	}
	$sql0 = "SELECT salary, work_hours FROM (user NATURAL JOIN employee) WHERE id = {$_SESSION['id']}";
	$result = mysqli_query($con, $sql0);
	if (mysqli_num_rows($result) > 0) {
		// output data of each row
		$data0 .= "<table><tr><th style=\"text-align:left\" width=\"20%\">Salary</th>
		<th style=\"text-align:left\" width=\"20%\">Work Hours</th></tr>";
		while($row = mysqli_fetch_assoc($result)) {
			$data0 .= "<tr><td width=\"20%\">" . number_format($row["salary"]).
			"</td><td width=\"20%\">" . $row["work_hours"]. 
			"</td></tr>";
		}
		$data0 .= "</table>";
	} 
	else {
		$data0 .= "Employee specific data is not set.";
	}
	$data0 .= "</div>";
}
$home_user_type .= "\">Go to your main page</a>";

// display info from a specific table (customer, tech_staff, customer_service), we can do it like this because in all 3 of these tables no of attributes is same.
$data1 = "";
$set_info = "";
$change_info = "<a href=\"../change/change_info.php\"><i class=\"fas fa-user-cog\"></i>Change User Info</a>";
$sql1 = "SELECT * FROM (user NATURAL JOIN $userType) WHERE id = {$_SESSION['id']}";
$result = mysqli_query($con, $sql1);
if (mysqli_num_rows($result) > 0) {
	// output data of each row
	$data1 .= "<table><tr><th style=\"text-align:left\" width=\"20%\">ID</th>";
	if( $_SESSION['type'] == 1 ){
		$data1 .= "<th style=\"text-align:left\" width=\"20%\">Birthday</th></tr>";
	}
	else if( $_SESSION['type'] == 2 ){
		$data1 .= "<th style=\"text-align:left\" width=\"20%\">Expertise</th></tr>";
	}
	else if( $_SESSION['type'] == 3 ){
		$data1 .= "<th style=\"text-align:left\" width=\"20%\">Position</th></tr>";
	}

    while($row = mysqli_fetch_assoc($result)) {
		$data1 .= "<tr><td width=\"20%\">" . $row["id"]; 
		if( $_SESSION['type'] == 1 ){
			$data1 .= "</td><td width=\"20%\">" . $row["birthday"];
		}
		else if( $_SESSION['type'] == 2 ){
			$data1 .= "</td><td width=\"20%\">" . $row["expertise"];
		}
		else if( $_SESSION['type'] == 3 ){
			$data1 .= "</td><td width=\"20%\">" . $row["position"];
		}
		$data1 .= "</td></tr>";
    }
    $data1 .= "</table>";
} 
else {
	$data1 .= "User specific information is not set.";
	$set_info = "<a href=\"../set/set_info.php\"><i class=\"fas fa-user-tag\"></i>Set Detailed Info</a>";
	$change_info = "<a href=\"../change/change_info.php?not_set\"><i class=\"fas fa-user-cog\"></i>Change User Info</a>"; //specific info not set
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Home Page</title>
		<link href="../style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Maintenance System</h1>
				<a href="home.php"><i class="fas fa-home"></i>Home</a>
				<?=$change_info?>
				<?=$set_info?>
				<a href="../logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
		<div class="content">
			<h2>Home Page</h2>
			<p>Welcome, <?=$_SESSION['name']?></p>
			<div>
				<?=$home_user_type?>
			</div>
			<?=$data0?>
			<div>
				<p><b style="font-size:24px;">Your User Specific Information</b></p>
                <?=$data1?>
            </div>
		</div>
	</body>
</html>