<?php 
session_start();
require '/Common/Loggedin.php'; 

if (!isset($_SESSION["username"])){
    header("Location: Login.php");
}
?>

<div class="container">
    <h1>Create New Album</h1>
    <p>Welcome <?php echo $_SESSION["username"]; ?> not you?(Change user <a href="Login.php">here</a>)</p>
    
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <div class="form-group">
            <label>Title: </label>
            <input type="text" class="form-control" name="albumTitle">
        </div>        
    </form>
</div>

<?php require '/Common/Footer.php'; ?>