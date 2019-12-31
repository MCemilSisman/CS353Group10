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

// employee table inputs
$data0 = "<div>
            <label for=\"salary\">
                <i class=\"fas fa-dollar-sign\"></i>
            </label>
            <input type=\"text\" name=\"salary\" placeholder=\"Salary\" id=\"salary\">
        </div>
        <div>
            <label for=\"work_hours\">
                <i class=\"fas fa-clock\"></i>
            </label>
            <input type=\"text\" name=\"work_hours\" placeholder=\"Work Hours\" id=\"work_hours\">
        </div>";

// specific table (customer, tech_staff, customer_service) inputs
if( $_SESSION['type'] == 1 ){
    $info0 = "birthday";
    $info1 = "Birthday";
    $icon = "birthday-cake";
    $data0 = ""; //not an employee
    $set_user_type = "set_customer.php";
}
else if( $_SESSION['type'] == 2 ){
    $info0 = "expertise";
    $info1 = "Expertise";
    $icon = "tools";
    $set_user_type = "set_tech_staff.php";
}
else if( $_SESSION['type'] == 3 ){
    $info0 = "position";
    $info1 = "Position";
    $icon = "user-tie";
    $set_user_type = "set_customer_service.php";
}
$data1 = "<div>
            <label for=\"" . $info0 . "\">
                <i class=\"fas fa-" . $icon . "\"></i>
            </label>
            <input type=\"text\" name=\"" . $info0 . "\" placeholder=\"" . $info1 . "\" id=\"" . $info0 . "\">
        </div>";
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Set Detailed Info</title>
		<link href="../style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Set Detailed Info</h1>
				<a href="../home/home.php"><i class="fas fa-arrow-alt-circle-left"></i>Back</a>
				<a href="../logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
		<div class="content">
			<div>
                <form action=<?=$set_user_type?> method="post">
                    <?=$data0?>       
                    <?=$data1?>           
                    <div>
                        <input type="submit" value="Submit" name="submit">
                    </div>
                </form>
			</div>
		</div>
	</body>
</html>