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
        $board_id = $_POST["board_id"];

        $statement = mysqli_prepare($con, "SELECT * FROM love WHERE user_id = ? and board_id = ?");
        mysqli_stmt_bind_param($statement, "ii", $user_id, $board_id);
        mysqli_stmt_execute($statement);

        mysqli_stmt_store_result($statement);
        mysqli_stmt_bind_result($statement, $love_id, $user_id, $board_id);

        $response = array();

        while(mysqli_stmt_fetch($statement))
        {
		$response["love_id"] = $love_id;
		$response["board_id"] = $board_id;
		$response["user_id"] = $user_id;
        }

        $response["success"] = false;

}
echo json_encode($response);
?>
