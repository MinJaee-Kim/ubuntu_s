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

$response["token_value"] = $jwt;
$response["success"] = false;

$statement = mysqli_prepare($con, "SELECT user_id, is_auto_log FROM token WHERE token_value = ?");
mysqli_stmt_bind_param($statement, "s", $jwt);
mysqli_stmt_execute($statement);

mysqli_stmt_store_result($statement);
mysqli_stmt_bind_result($statement, $user_id, $is_auto_log);

while(mysqli_stmt_fetch($statement))
{
	$response["user_id"] = $user_id;
	$response["is_auto_log"] = $is_auto_log;
}

if($destroy == 1)
{
        if($response["is_auto_log"] == 0)
        {
                $statement = mysqli_prepare($con, "DELETE FROM token WHERE token_value = ?");
                mysqli_stmt_bind_param($statement, "s", $jwt);
                mysqli_stmt_execute($statement) or die('this user is already in use') ;

                mysqli_commit($con);

                $response["success"] = true;
        }
}
else
{
        $statement = mysqli_prepare($con, "DELETE FROM token WHERE token_value = ?");
        mysqli_stmt_bind_param($statement, "s", $jwt);
        mysqli_stmt_execute($statement) or die('this user is already in use') ;

        mysqli_commit($con);

        $response["success"] = true;
}
echo json_encode($response);
?>
