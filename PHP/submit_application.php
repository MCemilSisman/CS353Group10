<?php
include 'config.php';
session_start();

// connect using credentials
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) { //connection error
	die ('Cannot connect to the database: ' . mysqli_connect_error());
}
// check if id field is filled
if ( $_POST['id'] == "" ) {
    header("Location: apply.php?Message=" . urlencode("ID field can't be empty"));
}
else{
    // check if entered id is a valid candidate for this student
    $applySQL = "SELECT cid FROM (
                    SELECT DISTINCT cid, t1.cname as cname FROM
                        ( SELECT cid, cname FROM company 
                        WHERE cid NOT IN 
                            ( SELECT cid FROM apply NATURAL JOIN company WHERE sid = '{$_SESSION['id']}' )
                        ) AS t1
                        INNER JOIN
                        ( SELECT cid, cname FROM company c1 WHERE
                        c1.quota > (SELECT COUNT(sid) FROM apply WHERE cid = c1.cid)
                        ) AS t2 
                        USING(cid)
                    ) AS t
                    WHERE cid = '{$_POST['id']}'";
    $applyResult = mysqli_query($con, $applySQL);
    if (mysqli_num_rows($applyResult) > 0) { // it is valid so insert
        $insertSQL = "INSERT INTO apply (sid,cid) VALUES ({$_SESSION['id']},'{$_POST['id']}');";
        if(mysqli_query($con, $insertSQL)){
            header("Location: home.php?Message=" . urlencode("New application successful"));
        }
        else{
            header("Location: home.php?Message=" . urlencode("Application NOT successful"));
        }
    }
    else {
        header("Location: apply.php?Message=" . urlencode("Invalid Company ID"));
    }
}
?>