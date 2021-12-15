<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

include 'dbcon.php';

$database = new Database();
$con = $database->getConnection();
$response = array();

$board_id = $_POST["board_id"];

$query = "SELECT a.board_id, a.user_id, a.bo_title, a.bo_cont, a.bo_date, a.bo_love, a.view_count, b.nickname, b.user_photo_uri FROM board AS a LEFT JOIN member AS b ON a.user_id = b.user_id WHERE a.board_id = $board_id ORDER BY a.bo_date DESC";

$statement = mysqli_query($con, $query);

if (mysqli_num_rows($statement) > 0)
{
        while($row = mysqli_fetch_assoc($statement))
        {
                array_push($response, $row);
        }
}
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
