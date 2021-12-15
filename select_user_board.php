<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

include 'dbcon.php';

$database = new Database();
$con = $database->getConnection();
$response = array();

$user_id = $_POST["user_id"];


$query = "SELECT a.board_id, a.user_id, a.bo_title, a.bo_cont, a.bo_date, a.bo_love, a.view_count, b.nickname, b.user_photo_uri, c.latitude, c.longitude FROM board AS a LEFT JOIN member AS b ON a.user_id = b.user_id LEFT JOIN board_location AS c ON a.board_id = c.board_id WHERE a.user_id = $user_id ORDER BY a.bo_date DESC";

$statement = mysqli_query($con, $query);

if (mysqli_num_rows($statement) > 0){
        while($row = mysqli_fetch_assoc($statement))
           {
                        array_push($response, $row);
           }
}
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
