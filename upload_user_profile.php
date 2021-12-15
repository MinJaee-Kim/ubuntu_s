<?php
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
        $user_id = $tokendata["jwt_payload"]["user_id"];
	
	$uploads_dir = '../user_image';
	$dbuploads_dir = 'http://150.230.136.110/user_image';

	$allowed_ext = array('jpg','jpeg','png','gif');

	$error = $_FILES['uploaded_file']['error'];
	$oldname = $_FILES['uploaded_file']['name'];

	$newname = $user_id.'_'.$_FILES['uploaded_file']['name'];

	rename("$oldname", "$newname");

	$ext = array_pop(explode('.', $newname));

	$response = array();

	if( $error != UPLOAD_ERR_OK ) {
       
		switch( $error ) {
		case UPLOAD_ERR_INI_SIZE:
		case UPLOAD_ERR_FORM_SIZE:
			$response["message"] = "파일이 너무 큽니다. ($error)";
			echo json_encode($response, JSON_UNESCAPED_UNICODE);
			break;
		case UPLOAD_ERR_NO_FILE:
			$response["message"] = "파일이 첨부되지 않았습니다. ($error)";
	   		echo json_encode($response, JSON_UNESCAPED_UNICODE);
	   		break;
		default:
	   		$response["message"] = "파일이 제대로 업로드되지 않았습니다. ($error)";
	   		echo json_encode($response, JSON_UNESCAPED_UNICODE);
		}
		exit;
	}

	if(!in_array($ext, $allowed_ext) ) {
		$response["message"] = "허용되지 않는 확장자입니다. ($ext)";
		echo json_encode($response, JSON_UNESCAPED_UNICODE);
		exit;
	}

	if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'], "$uploads_dir/$newname")) 
	{
		$response["user_photo_uri"] = "$dbuploads_dir/$newname";
		$response["message"] = "파일 업로드 성공했습니다.";
		$response["success"] = true;
	}
       	else
	{
		$response["success"] = false;
	}
}
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
