<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

include 'dbcon.php';

$database = new Database();
$con = $database->getConnection();
$response = array();


$left_latitude = $_POST["left_latitude"];
$left_longitude = $_POST["left_longitude"];

$right_latitude = $_POST["right_latitude"];
$right_longitude = $_POST["right_longitude"];

$query = "SELECT * FROM board_location WHERE latitude BETWEEN $left_latitude AND $right_latitude AND longitude BETWEEN $left_longitude AND $right_longitude";

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
