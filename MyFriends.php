<?php
session_start();
require 'Common/Loggedin.php'; 
require 'Common/Validation.php';
require 'dbconnection.php';

if (!isset($_SESSION["username"])){
    header("Location: Login.php");
}

?>

<div class="container">
    <h1 class="center">My Friends</h1>
    <p>Welcome <?php echo $_SESSION["name"]?>! (not you? change user <a href="Login.php">here!</a>)</p>
    <p><a href="AddFriend.php">Add Friends</a></p>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST">
        <table class="table">
        <tr>
            <th>Name</th>
            <th>Shared Albums</th>
            <th>Defriend</th>            
        </tr>
    </table>
        <button type="submit" class="btn btn-primary">Defriend Selected</button>
    </form>
    <br>
    <br>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST">
        <p>Friend Requests:</p>
        <table class="table">
            <tr>
                <th>Name</th>
                <th>Accept or Deny</th>
            </tr>
            <tr>
                <?php    
                    $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
                    $user = $_SESSION['username'];
                    $sql = "SELECT Friend_Requesterid FROM Friendship WHERE status='request' and Friend_Requesteeid=?";
                    
                    $stmt = mysqli_prepare($link, $sql);
                    mysqli_stmt_bind_param($stmt, 's', $requestee);
                    $requestee = $user;
                    
                    if(mysqli_stmt_execute($stmt)){
                        mysqli_stmt_bind_result($stmt, $requester);
                        if (mysqli_stmt_fetch($stmt)){
                            echo "<td>$requester</td><td><input type='checkbox' name='$requester' value='$requester'></td>";   
                        }                        
                    }
                ?>
            </tr>            
        </table>
        <button type="submit" class="btn btn-primary" value="accept">Accept Selected</button>            
        <button type="submit" class="btn btn-primary" value="denny">Deny Selected</button>
    </form>
</div>

<?php require 'Common/Footer.php' ;?>

