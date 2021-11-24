<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

include 'dbcon.php';

$database = new Database();
$con = $database->getConnection();

$statement = mysqli_query($con, "SELECT a.board_id, a.seq, a.bo_title, a.bo_cont, a.bo_date, a.bo_love, a.view_count, b.photo_id, b.photo_uri, b.photo_index,c.latitude, c.longitude FROM board AS a LEFT JOIN board_photo AS b ON a.board_id = b.board_id LEFT JOIN board_location AS c ON a.board_id = c.board_id");

if (mysqli_num_rows($statement) > 0) 
{
	while($row = mysqli_fetch_assoc($statement)) 
	{
		echo json_encode($row, JSON_UNESCAPED_UNICODE);
	}
}
?>
