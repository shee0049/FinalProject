<?php 
session_start();
require 'Common/Header.php'; 
require 'Common/Validation.php';
require 'dbconnection.php';

if (!isset($_SESSION["username"])){
    header("Location: Login.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userid = trim($_POST["studentID"]);

    if (ValidateUserID($userid)) {
        $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

        $userName = trim($_POST['studentID']);

        $sql = "SELECT Userid FROM User WHERE Userid = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, 's', $user);
            $user = $userName;

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $username);
                    if (mysqli_stmt_fetch($stmt)) {
                        echo "found user!" . $username;
                    } 
                }else {
                    $err_StudentId = "Could not find a user with that id.";
                }
            } else {
                $err_StudentId = "Could not find a user with that id.";
            }
        } else {
            echo "Something went horribly wrong!";
        }
        mysqli_stmt_close($stmt);
    }    
}
?>

<div class="container">
    <h1>Add Friend</h1>
    <p>Welcome <?php echo $_SESSION["name"]; ?> (not you? change user <a href="Login.php">here</a>) </p><br>
    <p>Enter the ID of the user you want to add.</p>
    
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <div class="form-group"> 
            <label for="studentID">User Name:</label>
            <input type="text" class="form-control" id="studentID" name="studentID">
            <span class="error"><?php echo $err_StudentId; ?></span>
        </div> 
        <button type="submit" class="btn btn-primary">Send Friend Request</button>
    </form>
</div>

<?php require 'Common/Footer.php'; ?>
