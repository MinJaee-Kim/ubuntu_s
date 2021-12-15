<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

include 'dbcon.php';

$database = new Database();
$con = $database->getConnection();

$user_id = $_POST["user_id"];
$token_value = $_POST["token_value"];

$response = array();

$statement = mysqli_prepare($con, "UPDATE login_history SET logout_time = (NOW()) WHERE user_id = ? AND token_value = ?");
mysqli_stmt_bind_param($statement, "is", $user_id, $token_value);
mysqli_stmt_execute($statement) or die('this user is already in use') ;

mysqli_commit($con);

$response["success"] = true;

echo json_encode($response);
?>
