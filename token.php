<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

require 'vendor/autoload.php';
use \Firebase\JWT\JWT;

function hashing(array $data)
{
        $secret_key = "this-is-the-secret";
        $alg = 'HS256';
        $jwt = JWT::encode($data, $secret_key, $alg);

        return $jwt;
}

function dehashing($token)
{
	$database = new Database();
	$con = $database->getConnection();

	$secret_key = "this-is-the-secret";
	$alg = 'HS256';
        $result = array();
        $decoded_array = array();

	$statement = mysqli_prepare($con, "SELECT token_value, is_using, is_auto_log FROM token WHERE token_value = ?");
    	mysqli_stmt_bind_param($statement, "s", $token);
	mysqli_stmt_execute($statement);
    
	mysqli_stmt_store_result($statement);   
	mysqli_stmt_bind_result($statement, $token, $is_using, $is_auto_log);
       
	$tokendata = array();

	while(mysqli_stmt_fetch($statement)) {
		$tokendata["token_value"] = $token;
	    	$tokendata["is_using"] = $is_using;
		$tokendata["is_auto_log"] = $is_auto_log;
       	}

	if($tokendata["is_using"] == 'Y')
	{
		try {
			$decoded = JWT::decode($tokendata["token_value"], $secret_key, array($alg));
	    		$decoded_array = (array)$decoded;
	    		$result = array();
			
			if($tokendata["is_auto_log"] == 1)
			{
				$result['message'] = "auto login";
			}
			else if ($decoded_array['exp'] < time() && $token["is_auto_log"] == 0) 
			{
				$result['message'] = "만료 오류";
	    			return $result;
		       	}
		       
			$result = array(
				'code' => 200,
	    			'status' => 'success',
	    			'jwt_payload' => $decoded_array
			);
	    
			return $result;
		}
		catch (Exception $e) 
		{
			$result['message'] = $e->getMessage().' Invalid JWT - Authentication failed!';
			return $result;
		}
	}
}
?>
