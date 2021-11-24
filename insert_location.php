<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

include 'dbcon.php';

$database = new Database();
$con = $database->getConnection();

$response = array();

$board_id = $_POST["board_id"];
$latitude = $_POST["latitude"];
$longitude = $_POST["longitude"];

$statement = mysqli_prepare($con, "INSERT INTO board_location(board_id, latitude, longitude) VALUES (?,?,?)");
mysqli_stmt_bind_param($statement, "idd", $board_id, $latitude, $longitude);
mysqli_stmt_execute($statement) or die('this user is already in use') ;

mysqli_commit($con);
$response["success"] = true;

echo json_encode($response);
?>
