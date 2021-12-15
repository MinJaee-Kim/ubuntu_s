<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

include 'dbcon.php';

$database = new Database();
$con = $database->getConnection();
$response = array();

$board_id = $_POST["board_id"];

$query = "select COUNT(board_id)AS count FROM love WHERE board_id = $board_id";

$statement = mysqli_query($con, $query);

$row = mysqli_fetch_assoc($statement);

echo json_encode($row, JSON_UNESCAPED_UNICODE);
?>
