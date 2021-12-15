<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

include 'dbcon.php';

$database = new Database();
$con = $database->getConnection();
$response = array();

$board_id = $_POST["board_id"];

$query = "DELETE FROM `board` WHERE `board`.`board_id` = '$board_id';";

if(mysqli_query($con, $query))
{
	$response["success"] = "true";
}

mysqli_close($con);

echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
