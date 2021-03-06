<?php
session_start();
require 'Common/Loggedin.php'; 
require 'Common/Validation.php';
require 'dbconnection.php';

if (!isset($_SESSION["username"])){
    header("Location: Login.php");
}
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
$user = $_SESSION['username'];

if ($_SERVER["REQUEST_METHOD"] == "POST"){    
    if (isset($_POST["accept"])){
        $updateSql = "UPDATE Friendship SET status='accepted' WHERE Friend_Requesterid=?";
        $friends = $_POST["friends"];                
        foreach ($friends as $friend){
            $stmt = mysqli_prepare($link, $updateSql);
            mysqli_stmt_bind_param($stmt, 's', $f);
            $f = $friend;
            mysqli_stmt_execute($stmt);
        }
        header("Location:MyFriends.php");
    }
    else if (isset($_POST["deny"])){        
        $friends = $_POST["friends"];
        $deleteSql = "DELETE FROM Friendship WHERE (Friendship.Friend_Requesterid=? OR Friendship.Friend_Requesteeid=?) "
                . "AND (Friendship.Friend_Requesterid=? OR Friendship.Friend_Requesteeid=?) AND Status='request'";
        
        foreach ($friends as $friend) {
            $stmt = mysqli_prepare($link, $deleteSql);
            mysqli_stmt_bind_param($stmt, 'ssss', $a,$b,$c,$d);
            $a = $b = $user;
            $c = $d = $friend;
            
            mysqli_execute($stmt);
        }
        header("Location: MyFriends.php");
    }
    else {
        $deleteSql = "DELETE FROM Friendship WHERE (Friendship.Friend_Requesterid=? OR Friendship.Friend_Requesteeid=?) "
                . "AND (Friendship.Friend_Requesterid=? OR Friendship.Friend_Requesteeid=?) AND Status='accepted'";
        $defriends = $_POST["defriends"];
        
        foreach ($defriends as $defriend) {
            $stmt = mysqli_prepare($link, $deleteSql);
            mysqli_stmt_bind_param($stmt, 'ssss', $a,$b,$c,$d);
            $a = $b = $user;
            $c = $d = $defriend;
            
            mysqli_execute($stmt);
        }
        header("Location: MyFriends.php");
    }      
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
        <tr>
            <?php 
                $sql = "SELECT Friendship.Friend_Requesterid, Friendship.Friend_Requesteeid FROM Friendship"
                      . " WHERE (Friendship.Friend_Requesterid=? OR Friendship.Friend_Requesteeid=?)"
                      . " AND Friendship.Status='accepted'";                    
                
                $stmt = mysqli_prepare($link, $sql);
                mysqli_stmt_bind_param($stmt, 'ss', $rq, $re);
                $rq = $user;
                $re = $user;    
                if(mysqli_stmt_execute($stmt)){
                    mysqli_stmt_bind_result($stmt, $requester, $requestee); 
                    $userArray = array();
                    while (mysqli_stmt_fetch($stmt)){                        
                        if($requestee == $user){
                            array_push($userArray, $requester);                           
                        }else {
                            array_push($userArray, $requestee);
                        }                        
                    }
                    mysqli_stmt_close($stmt);
                    foreach ($userArray as $friend) {
                        $sharedSql = "SELECT COUNT(Album.Title) FROM Album WHERE Album.Owner_Id=? AND Album.Accessibility_Code='shared'";
                        $stmt = mysqli_prepare($link, $sharedSql);
                        mysqli_stmt_bind_param($stmt, 's', $u);
                        $u = $friend;
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_bind_result($stmt, $shared);
                        mysqli_stmt_fetch($stmt);
                        echo "<tr><td><a href='FriendPictures.php?userid=$friend'>$friend</a></td><td>$shared</td><td><input type='checkbox' name='defriends[]' value='$friend'></td></tr>";
                        mysqli_stmt_close($stmt);
                    }
                }
            ?>
        </tr>
    </table>
        <input type="submit" class="defriend btn btn-primary" name="defriend" value="Defriend Selected">
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
                    $sql = "SELECT Friend_Requesterid FROM Friendship WHERE status='request' and Friend_Requesteeid=?";                    
                    $stmt = mysqli_prepare($link, $sql);
                    mysqli_stmt_bind_param($stmt, 's', $requestee);
                    $requestee = $user;
                    
                    if(mysqli_stmt_execute($stmt)){
                        mysqli_stmt_bind_result($stmt, $requester);                                          
                        while (mysqli_stmt_fetch($stmt)){
                            if (isset($requester)){
                                echo "<tr><td>$requester</td><td><input type='checkbox' name='friends[]' value='$requester'></td></tr>";   
                            }
                        }
                    }
                ?>
            </tr>            
        </table>
        <input type="submit" class="btn btn-primary" name="accept" value="Accept Selected">          
        <input type="submit" class="deny btn btn-primary" name="deny" value="Deny Selected">
    </form>
</div>
<?php require 'Common/Footer.php' ;?>

