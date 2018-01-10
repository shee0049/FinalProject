<?php 
session_start();
require 'Common/Header.php';
require 'Common/Validation.php';
require 'dbconnection.php';
include 'Common/Class_Lib.php';
include 'Common/Helpers.php';
?>

<?php 

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    // Pass Validation, Once passed. Input user into DataBase   
    if (validateSignUp()){
        $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        $student = new User(
                mysql_real_escape_string(trim($_POST['studentID'])),
                mysql_real_escape_string(trim($_POST['studentName'])), 
                mysql_real_escape_string(trim($_POST['studentPhone'])),
                mysql_real_escape_string(trim(sha1($_POST['studentPass']))));
        
        // check to make sure username isn't already taken.
        $err = checkUser($student->getUserId());
        
        if ($err){
            $err_StudentId = "User is already in system.";
        }                
        else {
            $insertUser = "INSERT INTO User VALUES (?,?,?,?)";
            $insertUserStmt = mysqli_prepare($link, $insertUser);
        
            mysqli_stmt_bind_param($insertUserStmt, 'ssss', $student->getUserId(), $student->getName(), $student->getPhone(), $student->getPassword());
            mysqli_stmt_execute($insertUserStmt);
            mysqli_close($link);
            header("Location: index.php");
        }         
    }
}
?>


<div class="container">
    <h1>Sign Up</h1>
    <p>All form fields required</p>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST">
        <div class="form-group"> 
            <label for="studentID">User Name:</label>
            <input type="text" class="form-control" id="studentID" name="studentID" value="<?php echo isset($_POST['studentID']) ? $_POST['studentID'] : ''; ?>">
            <span class="error"><?php echo $err_StudentId;?></span>
        </div>            
        <div class="form-group">
            <label for="studentName">Student Name:</label>
            <input type="text" class="form-control" id="studentName" name="studentName" value="<?php echo isset($_POST['studentName']) ? $_POST['studentName'] : ''; ?>">
            <span class="error"><?php echo $err_Name;?></span>
        </div>
        <div class="form-group">
            <label for="studentPhone">Phone Number:</label>
            <input type="text" class="form-control" id="studentPhone" name="studentPhone" value="<?php echo isset($_POST['studentPhone']) ? $_POST['studentPhone'] : ''; ?>">
            <span class="error"><?php echo $err_phone;?></span>
        </div>
        <div class="form-group">
            <label for="studentPass">Password:</label>
            <input type="password" class="form-control" id="studentPass" name="studentPass">
            <span class="error"><?php echo $err_pass ?></span>
        </div>
        <div class="form-group">
            <label for="verifyPass">Password Again:</label>
            <input type="password" class="form-control" id="verifyPass" name="verifyPass">
            <span class="error"><?php echo $err_verification ?></span>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<?php require 'Common/Footer.php';?>