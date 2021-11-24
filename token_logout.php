<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

include 'dbcon.php';

$database = new Database();
$con = $database->getConnection();

$destroy = $_POST["destroy"];    

$header = apache_request_headers();
foreach ($header as $headers => $value) {
	if($headers == 'token')
	{
		$jwt = $value;
	}
}

$response = array();
$response["success"] = false;
    
if($destroy == 1)
{
    	$statement = mysqli_prepare($con, "SELECT is_auto_log FROM token WHERE token = ?");
        mysqli_stmt_bind_param($statement, "s", $jwt);
        mysqli_stmt_execute($statement);

        mysqli_stmt_store_result($statement);
        mysqli_stmt_bind_result($statement, $is_auto_log);

        $tokendata = array();

	while(mysqli_stmt_fetch($statement)) 
	{
                $tokendata["is_auto_log"] = $is_auto_log;
	}
	if($tokendata["is_auto_log"] == 0)
	{
	    	$statement = mysqli_prepare($con, "UPDATE token SET isusing = 'N' where token = ? and is_auto_log = 0");
	    	mysqli_stmt_bind_param($statement, "s", $jwt);
	    	mysqli_stmt_execute($statement) or die('this user is already in use') ;

	    	mysqli_commit($con);

	    	$response["success"] = true;
	}
}
else
{
	$statement = mysqli_prepare($con, "UPDATE token SET isusing = 'N', is_auto_log = 0 where token = ?");
    	mysqli_stmt_bind_param($statement, "s", $jwt);
    	mysqli_stmt_execute($statement) or die('this user is already in use') ;

    	mysqli_commit($con);

    	$response["success"] = true;
}
echo json_encode($response);
?>
