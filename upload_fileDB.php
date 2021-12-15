<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

include 'dbcon.php';

$database = new Database();
$con = $database->getConnection();

$board_id = $_POST["board_id"];
$photo_uri = $_POST["photo_uri"];
$photo_index = $_POST["photo_index"];

 $statement = mysqli_prepare($con, "INSERT INTO board_photo(board_id, photo_uri, photo_index) VALUES (?,?,?)");
        mysqli_stmt_bind_param($statement, "isi", $board_id, $photo_uri, $photo_index);
        mysqli_stmt_execute($statement) or die('this user is already in use') ;

        mysqli_commit($con);
	$response = array();
	    $response["success"] = true;
	
	echo json_encode($response);
?>
