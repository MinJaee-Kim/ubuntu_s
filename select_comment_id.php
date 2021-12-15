<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

include 'dbcon.php';

$database = new Database();
$con = $database->getConnection();
$response = array();

$comment_id = $_POST["comment_id"];

$query = "SELECT a.comment_id, a.board_id, a.user_id, a.co_cont, a.co_love, a.co_date, b.nickname, b.user_photo_uri FROM board_comment AS a LEFT JOIN member AS b ON a.user_id = b.user_id WHERE comment_id = $comment_id ORDER BY a.co_date ASC";

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
