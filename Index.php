<?php 
session_start();
require "Common/Header.php"; 
if (isset($_SESSION["username"])){
    echo 'Hello!'. $_SESSION["username"];
}
?>
<div class="container">
    <h1>Welcome to the Algonquin Social Media Website</h1>
    <p>If this is your first time using click <a href="SignUp.php">here!</a> to sign up</p>
    <p>Otherwise <a href="Login.php">here</a> to log in</p>
</div>
<?php require 'Common/Footer.php'; ?>