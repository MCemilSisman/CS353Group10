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

// message to display if an application is submitted
if (isset($_GET['Message'])) {
    print '<script type="text/javascript">alert("' . $_GET['Message'] . '");</script>';
}

// prepare sql stament
$sql = "SELECT cid FROM apply WHERE sid = '{$_SESSION['id']}'";
$result = mysqli_query($con, $sql);
if (mysqli_num_rows($result) > 2) {
	header("Location: home.php?Message=" . urlencode("Can't apply to more than 3 companies"));
}
else{
	$data = "";
	// sql query to find candidate companies for a student
	// we find the companies who did not meet their quota, and the companies the student has not applied yet, and get the intersection of them.
	$applySQL = "SELECT DISTINCT cid, t1.cname as cname FROM
					( SELECT cid, cname FROM company 
					WHERE cid NOT IN 
						( SELECT cid FROM apply NATURAL JOIN company WHERE sid = '{$_SESSION['id']}' )
					) AS t1
					INNER JOIN
					( SELECT cid, cname FROM company c1 WHERE
					c1.quota > (SELECT COUNT(sid) FROM apply WHERE cid = c1.cid)
					) AS t2 
					USING(cid)";
	$applyResult = mysqli_query($con, $applySQL);
	if (mysqli_num_rows($applyResult) > 0) {
		// output data of each row
		$data .= "<table><tr><th>Company ID</th><th>Company Name</th></tr>";
		while($row = mysqli_fetch_assoc($applyResult)) {
			$data .= "<tr><td>" . $row["cid"] . "</td><td>" . $row["cname"] . "</td></tr>";
		}
		$data .= "</table>";
	} 
	else {
		$data .= "0 results";
	}

}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Application Page</title>
		<link href="style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Summer Internship System</h1>
				<a href="home.php"><i class="fas fa-arrow-alt-circle-left"></i>Back</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
		<div class="content">
			<h2>Application Page</h2>
			<div>
				<p>Available Companies</p>
                <?=$data?>
			</div>
			<form action="submit_application.php" method="post">
				<input type="id" name="id" placeholder="Enter company ID" id="id">
				<input type="submit" value="Submit">
			</form>
		</div>
	</body>
</html>