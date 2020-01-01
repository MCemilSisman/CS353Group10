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

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Create Spare Part Request</title>
		<link href="../style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Create Spare Part Request</h1>
				<a href="../home/home_tech_staff.php"><i class="fas fa-arrow-alt-circle-left"></i>Back</a>
				<a href="../logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
		<div class="content">
			<div>
                <form action="submit_spare_part_request.php" method="post">
                    <div>
                        <label for="repair_request_id">
                            <i class="fas fa-wrench"></i>
                        </label>
                        <input type="text" name="repair_request_id" placeholder="Repair Request ID" id="repair_request_id">
                    </div>
                    <div>
                        <label for="spare_part_name">
                            <i class="fas fa-band-aid"></i>
                        </label>
                        <input type="text" name="spare_part_name" placeholder="Spare Part Name" id="spare_part_name">
                    </div>        
                    <div>
                        <input type="submit" value="Submit" name="submit">
                    </div>
                </form>
			</div>
		</div>
	</body>
</html>