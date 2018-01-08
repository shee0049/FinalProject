<?php
require_once 'dbconnection.php';
function checkUser ($userId){
    
    $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    
    $selectUser = "SELECT Userid FROM User WHERE Userid = ?";
    $selectUserStmt = mysqli_prepare($link, $selectUser);

    mysqli_stmt_bind_param($selectUserStmt, 's', $userId);
    mysqli_stmt_execute($selectUserStmt);
    $user = mysqli_stmt_fetch($selectUserStmt);

    if($user){
        mysqli_close($link);
        return TRUE;            
    }
    mysqli_close($link);
    return FALSE;
}