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

//get complaint_id
$complaint_id = "";
$product_name = "";
$userType = "";
$customerType = "";
$customerServiceType = "";
if (isset($_GET['id'])) {
    $complaint_id = $_GET['id'];
    $product_name = $_GET['pname'];
    $userType = $_GET['userType'];;
}

if( $userType == "customer" ){
    $customerType = "userType";
    $customerServiceType = "otherType";
}
else{
    $customerType = "otherType";
    $customerServiceType = "userType";
}

// prepare sql statement for repair request
$topic = "";
$explanation = "";
$conversation = "";
$conversation_no = 1;
$sql0 = "SELECT topic, explanation FROM complaint WHERE complaint_id = $complaint_id";
$result0 = mysqli_query($con, $sql0);
if (mysqli_num_rows($result0) > 0) {
    $row0 = mysqli_fetch_assoc($result0);
    $topic = $row0["topic"];
    $explanation = $row0["explanation"];
    
    $sql1 = "SELECT conversation_no, customer_msg, customer_msg_date, customer_service_msg, customer_service_msg_date FROM (complaint NATURAL JOIN conversation) WHERE complaint_id = $complaint_id";
    $result1 = mysqli_query($con, $sql1);
    if (mysqli_num_rows($result1) > 0) {
        // output data of each row
        while($row1 = mysqli_fetch_assoc($result1)) {
            if($row1["customer_msg"] != "null"){
                $conversation .= "<div class=\"". $customerType . "\">
                                    [" . $row1["customer_msg_date"] . "]: " . $row1["customer_msg"] .
                                "</div>";
            }
            if($row1["customer_service_msg"] != "null"){
                $conversation .= "<div class=\"". $customerServiceType . "\">
                                    [" . $row1["customer_service_msg_date"] . "]: " . $row1["customer_service_msg"] .
                                "</div>";
                $conversation_no++;
            }
        }
    }
    else{
        $conversation .= "<div>
                           Got something to say? Start the conversation!
                        </div>";
    }
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Repair Request</title>
		<link href="../style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Maintenance System</h1>
				<a href="../home/home_<?=$userType?>.php"><i class="fas fa-arrow-alt-circle-left"></i>Back</a>
				<a href="../logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
		<div class="content">
            <h2><?=$complaint_id?> / <?=$product_name?> / <?=$topic?></h2>
            <div>
                <p><b style="font-size:24px;">Explanation</b></p>
                <?=$explanation?> 
            </div>
            <div class="chat">
                <p><b style="font-size:24px;">Chat</b></p>
                <?=$conversation?>
                <form action="../create/submit_message.php?userType=<?=$userType?>&complaint_id=<?=$complaint_id?>&conversation_no=<?=$conversation_no?>" method="post">
                    <div>
                        <label for="message">
                            <i class="fas fa-envelope"></i>
                        </label>
                        <textarea rows="3" cols="100" type="text" name="message" placeholder="Send a message" id="message"></textarea>
                    </div>          
                    <div>
                        <input type="submit" value="Send" name="submit">
                    </div>
                </form>
            </div>
		</div>
	</body>
</html>