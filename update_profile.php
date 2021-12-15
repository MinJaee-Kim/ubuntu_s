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
        $user_photo_uri = $_POST["user_photo_uri"];

        $statement = mysqli_prepare($con, "UPDATE member SET user_photo_uri = ? where user_id = ?");
	mysqli_stmt_bind_param($statement, "si", $user_photo_uri, $user_id);
	mysqli_stmt_execute($statement) or die('this user is already in use') ;

	mysqli_commit($con);
	$response["success"] = true;
}
echo json_encode($response);
?>
