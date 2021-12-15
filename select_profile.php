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

if($tokendata["status"] == 'success')
{
        $user_id = $tokendata["jwt_payload"]["user_id"];
        $query = "SELECT * FROM member WHERE user_id = $user_id";

        $statement = mysqli_query($con, $query);

        if (mysqli_num_rows($statement) > 0)
        {
                while($row = mysqli_fetch_assoc($statement))
                {
                        array_push($response, $row);
                }
        }
}
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
