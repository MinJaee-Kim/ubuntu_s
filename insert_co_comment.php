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
        $comment_id = $_POST["comment_id"];
        $co_co_cont = $_POST["co_co_cont"];

        $statement = mysqli_prepare($con, "INSERT INTO co_comment(comment_id, user_id, co_co_cont, co_co_date) VALUES (?,?,?,NOW())");
        mysqli_stmt_bind_param($statement, "iis", $comment_id, $user_id, $co_co_cont);
        mysqli_stmt_execute($statement) or die('this user is already in use') ;

        mysqli_commit($con);

        $response["success"] = true;

        echo json_encode($response);
}
?>
