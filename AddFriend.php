<?php 
session_start();
require 'Common/Loggedin.php'; 
require 'Common/Validation.php';
require 'dbconnection.php';

if (!isset($_SESSION["username"])){
    header("Location: Login.php");
}

$currentUser = $_SESSION["username"];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userid = trim($_POST["studentID"]);

    if (ValidateUserID($userid)) {
        if ($userid == $_SESSION["username"]){
            $err_StudentId="you cannot add your own username";
        }
        else {
            $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

            $sql = "SELECT Userid FROM User WHERE Userid = ?";
            // Get user that is entered on form from the database
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, 's', $user);
                $user = $userid;

                if (mysqli_stmt_execute($stmt)) {
                                                  
                    mysqli_stmt_store_result($stmt);
                    // If the user is found continue, otherwise error.
                    if (mysqli_stmt_num_rows($stmt) == 1) {
                        mysqli_stmt_bind_result($stmt, $username);
                        if (mysqli_stmt_fetch($stmt)) {
                            // Send friend request to user
                            $fsql = "INSERT INTO Friendship VALUES (?,?,?)";
                            $friendshipStmt = mysqli_prepare($link, $fsql);
                            $status = 'request';                        
                            mysqli_stmt_bind_param($friendshipStmt, 'sss', $currentUser, $userid, $status);                                              
                            if(mysqli_execute($friendshipStmt)){
                                $friendmsg = "Your friend request has been sent to " . $user . " Once " . $user . 
                                        " Accepts your request. You and " . $user . 
                                        " will be friends and be able to view each others shared albums.";
                            }  
                            else {
                                $friendmsg = "There was a problem with your request, please try again.";
                            }
                        }
                    } else {
                    $err_StudentId = "Could not find a user with that id.";
                    }
                }
            } else {
                $err_StudentId = "Could not find a user with that id.";
              }
            mysqli_stmt_close($stmt);
            mysqli_close($link);
        }        
    }
}
            
?>

<div class="container">
    <h1>Add Friend</h1>
    <p>Welcome <?php echo htmlspecialchars($_SESSION["name"]); ?> (not you? change user <a href="Login.php">here</a>) </p><br>
    <p>Enter the ID of the user you want to add.</p>
    <span class="error"><?php echo $friendmsg ?></span>
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
