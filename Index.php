<?php 
session_start();
if (isset($_SESSION["username"])){
    require 'Common/LoggedIn.php';
    echo "<div class='container'>";
    echo "<h1>Welcome $_SESSION[username], to the Algonquin Social Media Website</h1>";
    echo "</div>";
}
else {
    require 'Common/Header.php';
    echo "<div class='container'>";
    echo "<h1>Welcome to the Algonquin Social Media Website</h1>";
    echo "<p>If this is your first time using click <a href='SignUp.php'>here!</a> to sign up</p>";
    echo "<p>Otherwise <a href='Login.php'>here</a> to log in</p>";
    echo "</div>";
}
require 'Common/Footer.php'; ?>