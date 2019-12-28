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

// message to display if redirected from apply page or submit_application
if (isset($_GET['Message'])) {
    print '<script type="text/javascript">alert("' . $_GET['Message'] . '");</script>';
}

// prepare sql statement
$data = "";
$sql = "SELECT cid, cname, quota FROM (company NATURAL JOIN apply) WHERE sid = '{$_SESSION['id']}'";
$result = mysqli_query($con, $sql);
if (mysqli_num_rows($result) > 0) {
    // output data of each row
    $data .= "<table><tr><th>Company ID</th><th>Company Name</th><th>Quota</th></tr>";
    while($row = mysqli_fetch_assoc($result)) {
        $data .= "<tr><td>" . $row["cid"]. "</td><td>" . $row["cname"]. "</td><td>" . $row["quota"]. "</td><td><a href=\"cancel_application.php?cid=" . $row["cid"] . "\">Cancel</a></td></tr>";
    }
    $data .= "</table>";
} 
else {
    $data .= "0 results";
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Welcome Page</title>
		<link href="style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Summer Internship System</h1>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
		<div class="content">
			<h2>Welcome Page</h2>
            <p>Welcome, <?=$_SESSION['name']?></p>
            <div>
                <p>Your Applications</p>
                <?=$data?>
            </div>
            <a href="apply.php">Apply for New Internship</a>
		</div>
	</body>
</html>