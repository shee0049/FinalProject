<?php 
session_start();
require 'Common/Header.php';
require 'Common/Validation.php';
require 'dbconnection.php';

if ($_SERVER['REQUEST_METHOD']=="POST"){
    $userErr = testInput($_POST["studentID"]);
    $passErr = testInput($_POST["studentPass"]);

    if ($userErr == "" && $passErr == "") {
        $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);        
        $pass = trim(sha1($_POST['studentPass']));
        $userName = trim($_POST['studentID']);
        
        $sql = "SELECT Userid, Password, Name FROM User WHERE Userid = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, 's', $user);
            $user = $userName;
            
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);                
                if (mysqli_stmt_num_rows($stmt) == 1){
                    mysqli_stmt_bind_result($stmt, $username, $hashed_password, $name);
                    
                    if(mysqli_stmt_fetch($stmt)){
                        if($pass == $hashed_password){
                            session_start();
                            $_SESSION['username'] = $username;      
                            $_SESSION['name'] = $name;
                            header("location: Index.php");
                        }else {
                            $passErr = "Password is Incorrect";
                        }
                    }
                }else {
                    $userErr = "Username is Incorrect";
                }                    
            }else {
                echo "Something went horribly wrong!";
            }                
        } 
        mysqli_stmt_close($stmt);
    }
    mysqli_close($link);
}
?>

<div class="container">
    <h1>Log in</h1>
    <p>you need to <a href="SignUp.php">sign up</a> if you are a new user</p>
    
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST">
        <div class="form-group"> 
            <label for="studentID">User Name:</label>
            <input type="text" class="form-control" id="studentID" name="studentID" value="<?php echo isset($_POST['studentID']) ? $_POST['studentID'] : ''; ?>">
            <span class="error"><?php if(isset($userErr)) echo $userErr; ?></span>
        </div> 
        <div class="form-group">
            <label for="studentPass">Password:</label>
            <input type="password" class="form-control" id="studentPass" name="studentPass"> 
            <span class="error"><?php if(isset($userErr)) echo $passErr; ?></span>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<?php require 'Common/Footer.php';?>