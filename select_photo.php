<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

include 'dbcon.php';

$database = new Database();
$con = $database->getConnection();
$response = array();

$board_id = $_POST["board_id"];

$query = "SELECT photo_uri FROM board_photo WHERE board_id = $board_id ORDER BY photo_index ASC";

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
