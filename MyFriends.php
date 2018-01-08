<?php
session_start();

if (!isset($_SESSION["username"])){
    header("Location: Login.php");
}
require 'Common/Loggedin.php';
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
</div>

<?php require 'Common/Footer.php' ;?>

