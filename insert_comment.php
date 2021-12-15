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
$response = array();

$response["success"] = false;

if($tokendata["status"] == 'success')
{
        $user_id = $tokendata["jwt_payload"]["user_id"];
        $board_id = $_POST["board_id"];
        $co_cont = $_POST["co_cont"];

        $statement = mysqli_prepare($con, "INSERT INTO board_comment(board_id, user_id, co_cont, co_date) VALUES (?,?,?,NOW())");
        mysqli_stmt_bind_param($statement, "iis", $board_id, $user_id, $co_cont);
        mysqli_stmt_execute($statement) or die('this user is already in use') ;

        mysqli_commit($con);

        $response["success"] = true;
}
echo json_encode($response);
?>
