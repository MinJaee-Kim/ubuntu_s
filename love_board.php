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

        $statement = mysqli_prepare($con, "SELECT love_id FROM love WHERE user_id = ? and board_id = ?");
        mysqli_stmt_bind_param($statement, "ii", $user_id, $board_id);
        mysqli_stmt_execute($statement);

        mysqli_stmt_store_result($statement);
        mysqli_stmt_bind_result($statement, $love_id);

        $response = array();

        while(mysqli_stmt_fetch($statement))
        {
                $response["love_id"] = $love_id;
        }

        $response["success"] = false;

        if($response["love_id"] != null) {
	    $statement = mysqli_prepare($con, "DELETE FROM love WHERE love_id = ?");
                mysqli_stmt_bind_param($statement, "i", $response["love_id"]);
                mysqli_stmt_execute($statement) or die('this user is already in use') ;

                mysqli_commit($con);

                $statement = mysqli_prepare($con, "UPDATE board SET bo_love = bo_love - 1 where board_id = ?");
                mysqli_stmt_bind_param($statement, "i", $board_id);
                mysqli_stmt_execute($statement) or die('this user is already in use') ;

                mysqli_commit($con);

                $response["success"] = true;
        }
        else
        {
                $statement = mysqli_prepare($con, "INSERT INTO love(user_id, board_id) VALUES (?,?)");
                mysqli_stmt_bind_param($statement, "ii", $user_id, $board_id);
                mysqli_stmt_execute($statement) or die('this user is already in use') ;

                mysqli_commit($con);

                $statement = mysqli_prepare($con, "UPDATE board SET bo_love = bo_love + 1 where board_id = ?");
                mysqli_stmt_bind_param($statement, "i", $board_id);
                mysqli_stmt_execute($statement) or die('this user is already in use') ;

                mysqli_commit($con);

                $response["success"] = true;
        }
}
echo json_encode($response);
?>
