<?php
error_reporting(E_ALL);
    ini_set('display_errors',1);

    include 'dbcon.php';
    include 'token.php';

    $database = new Database();
    $con = $database->getConnection();

    $username = $_POST["username"];
    $isAutoLog = $_POST["is_auto_log"];

    $statement = mysqli_prepare($con, "SELECT user_id, password FROM member WHERE username = ?");
    mysqli_stmt_bind_param($statement, "s", $username);
    mysqli_stmt_execute($statement);


    mysqli_stmt_store_result($statement);
    mysqli_stmt_bind_result($statement,$user_id, $password);

    $response = array();
    $response["success"] = false;

    while(mysqli_stmt_fetch($statement)) {
        $response["success"] = true;
        $response["cre"] = time();
        $response["exp"] = time() + (3600 * 24);
        $response["user_id"] = $user_id;
        $response["username"] = $username;
        $response["password"] = $password;
    }

    $response["token_value"] = hashing($response);

    $statement = mysqli_prepare($con, "INSERT INTO token(token_value, user_id, create_date, expire_date, is_auto_log) VALUES (?,?,?,?,?)");
    mysqli_stmt_bind_param($statement, "siiii", $response["token_value"], $response["user_id"], $response["cre"],$response["exp"], $isAutoLog);
    mysqli_stmt_execute($statement) or die('this user is already in use') ;

    mysqli_commit($con);

    echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
