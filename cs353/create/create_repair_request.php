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
		<title>Create Repair Request</title>
		<link href="../style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Create Repair Request</h1>
				<a href="../home/home_customer.php"><i class="fas fa-arrow-alt-circle-left"></i>Back</a>
				<a href="../logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
		<div class="content">
			<div>
                <form action="submit_repair_request.php" method="post">
                    <div>
                        <label for="pname">
                            <i class="fas fa-laptop"></i>
                        </label>
                        <input type="text" name="pname" placeholder="Product Name" id="pname">
                    </div>
                    <div>
                        <label for="explanation">
                            <i class="fas fa-file-alt"></i>
                        </label>
                        <textarea rows="10" cols="35" type="text" name="explanation" placeholder="Explanation" id="explanation"></textarea>
                    </div>          
                    <div>
                        <input type="submit" value="Submit" name="submit">
                    </div>
                </form>
			</div>
		</div>
	</body>
</html>