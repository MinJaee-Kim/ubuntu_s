<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

include 'dbcon.php';

$response = array();

$database = new Database();
$con = $database->getConnection();

$user_id = $_POST["user_id"];
$token_value = $_POST["token_value"];

$statement = mysqli_prepare($con, "INSERT INTO login_history(user_id, token_value,login_time) VALUES (?,?,NOW())");
mysqli_stmt_bind_param($statement, "is", $user_id, $token_value);
mysqli_stmt_execute($statement) or die('this user is already in use') ;

mysqli_commit($con);

$response["success"] = true;

echo json_encode($response);
?>
