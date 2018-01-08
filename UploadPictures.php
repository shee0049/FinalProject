<?php 
session_start();
require '/Common/Loggedin.php'; 

if (!isset($_SESSION["username"])){
    header("Location: Login.php");
}
?>
<div class="container">
    <h1>Upload Pictures</h1>
    <p>Accepted File formats: JPG, GIF and PNG</p>  
    <form>        
    </form>
</div>
<?php require '/Common/Footer.php';?>