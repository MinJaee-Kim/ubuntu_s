<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

include 'dbcon.php';
include 'token.php';

$database = new Database();
$con = $database->getConnection();

$header = apache_request_headers();
foreach ($header as $headers => $value) {
	if($headers == 'token')
	{
		$jwt = $value;
	}
}

$tokendata = array();
$tokendata = dehashing($jwt);

if($tokendata["status"] == 'success')
{
	$response = array();

        $user_id = $tokendata["jwt_payload"]["user_id"];
        $bo_title = $_POST["bo_title"];
        $bo_cont = $_POST["bo_cont"];

        $statement = mysqli_prepare($con, "INSERT INTO board(seq, bo_title, bo_cont, bo_date) VALUES (?,?,?,NOW())");
        mysqli_stmt_bind_param($statement, "iss", $user_id, $bo_title, $bo_cont);
        mysqli_stmt_execute($statement) or die('this user is already in use') ;

        $response["board_id"] = mysqli_insert_id($con);
        mysqli_commit($con);

        $response["success"] = true;

	echo json_encode($response);
}
?>
