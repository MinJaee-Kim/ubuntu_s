<?php
    error_reporting(E_ALL);
    ini_set('display_errors',1);
    $con = mysqli_connect("localhost", "root", "root1234!", "view_matzip");
    mysqli_query($con,'SET NAMES utf8');

    $username = $_POST["username"];
    $password = $_POST["password"];
    $nickname = $_POST["nickname"];

    $statement = mysqli_prepare($con, "INSERT INTO member(username, password, nickname, 
																		authority, enabled) VALUES (?,?,?,'ROLE_USER',1)");
    mysqli_stmt_bind_param($statement, "sss", $username, $password, $nickname);
    mysqli_stmt_execute($statement) or die('this user is already in use') ;

    mysqli_commit($con);

    $response = array();
    $response["success"] = true;

    echo json_encode($response);
?>
