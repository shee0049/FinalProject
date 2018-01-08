<?php
session_start();

if (!isset($_SESSION["username"])){
    header("Location: Login.php");
}
require 'Common/Loggedin.php';
?>

<div class="container">
    <h1 class="center">My Albums</h1>
    <p>Welcome <?php echo $_SESSION["name"]?>! (not you? change user <a href="Login.php">here!</a>)</p>
    <p><a href="AddAlbum.php">Create a New Album</a></p>
    <table class="table">
        <tr>
            <th>Title</th>
            <th>Date Updated</th>
            <th>Number of Pictures</th>
            <th>Accessibility</th>
        </tr>
    </table>
</div>

<?php require 'Common/Footer.php' ;?>

