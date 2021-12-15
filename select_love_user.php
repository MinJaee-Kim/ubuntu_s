<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

include 'dbcon.php';

$database = new Database();
$con = $database->getConnection();
$response = array();

$board_id = $_POST["board_id"];

$query = "SELECT nickname FROM member LEFT OUTER JOIN love
ON member.user_id = love.user_id
where love.board_id = $board_id
ORDER BY love.love_id ASC LIMIT 1";

$statement = mysqli_query($con, $query);

$row = mysqli_fetch_assoc($statement);

echo json_encode($row, JSON_UNESCAPED_UNICODE);
?>
