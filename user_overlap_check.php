<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

include 'dbcon.php';

$database = new Database();
$con = $database->getConnection();

$username = $_POST["username"];

$statement = mysqli_prepare($con, "SELECT username FROM member WHERE username = ?");
mysqli_stmt_bind_param($statement, "s", $username);
mysqli_stmt_execute($statement);

mysqli_stmt_store_result($statement);
mysqli_stmt_bind_result($statement, $username);

$response = array();

while(mysqli_stmt_fetch($statement))
{
	$response["username"] = $username;
}

$response["success"] = false;

if ($response["username"] != null)
{
	$response["success"] = false;
	$response["message"] = "중복된 회원이 있습니다.";
}
else
{
	$response["success"] = true;
}
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
