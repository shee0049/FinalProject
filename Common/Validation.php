<?php

function testInput($test){
    $err = "";
    if (empty($test)){
        $err = "Field is Required!";
        return $err;
    }
    else{
        return $err = "";
    }
}

function validateSignUp() {    
    $studentId = ValidateUserID($_POST["studentID"]);
    $name = ValidateName($_POST["studentName"]);
    $phone = ValidatePhone($_POST["studentPhone"]);
    $pass = ValidatePassword($_POST['studentPass'], $_POST['verifyPass']);
    
    if ($studentId && $name && $phone && $pass){
        return TRUE;
    }
    else {
        return FALSE;
    }
}

function ValidateUserID($studentID){
    $studentID = trim($studentID);
    global $err_StudentId;
    
    if (strlen($studentID) == 0){
        $err_StudentId = 'User Name cannot be blank';
        return false;
    }                  
    
    $err_StudentId = "";
    return TRUE;
}

function ValidateName($name){
    $name = trim($name);
    global $err_Name;
    
    if (strlen($name) == 0){
        $err_Name = 'Student Name cannot be blank';
        return false;
    }  
    $err_Name = "";
    return TRUE;
}

function ValidatePhone($phone) {
    $phone = trim($phone);
    global $err_phone;
    $regex = "/^[2-9]\d{2}-[2-9]\d{2}-\d{4}$/";

    if (!preg_match($regex, $phone)) {
        $err_phone = '*Incorrect phone number';
        return FALSE;
    }
    $err_phone = '';
    return TRUE;
}

function ValidatePassword($pass, $verifyPass){
    $pass = trim($pass);
    global $err_pass, $err_verification;

    $regex = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{6,}$/";
    if (!preg_match($regex, $pass)) {
        $err_pass = 'Password must contain at least 6 characters, at least one upper case, one lowercase and one digit';
        return FALSE;
    }
    
    if ($pass != $verifyPass){
        $err_verification = 'Passwords dont match';
        return FALSE;
    }
    $err_verification = '';
    $err_pass = '';
    return TRUE;
}
