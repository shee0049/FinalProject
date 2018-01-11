<?php
session_start();
require 'Common/LoggedIn.php';
require 'Common/Validation.php';
require 'dbconnection.php';
include 'Common/Class_Lib.php';
include 'Common/Helpers.php';

if (!isset($_SESSION["username"])){
    header("Location: Login.php");
}
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
$user = $_SESSION["username"];
$albumid = $_GET["albumid"];
?>

<div class="container">
    <h1>My Pictures</h1>
    <form>
        <select name="album">
        <?php 
        $sql = "SELECT Album_id, Title, Description, Date_Updated FROM Album WHERE Owner_Id=?";
        
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, 's', $u);
        $u = $user;
        if (mysqli_stmt_execute($stmt)){ 
            mysqli_stmt_bind_result($stmt,$id, $title, $description, $date);
            while (mysqli_stmt_fetch($stmt)){
                if($id == $albumid){
                    echo "<option selected>$title - updated on $date</option>";
                }
                else {
                    echo "<option>$title - updated on $date</option>";
                }
            }
        }
        
        ?>
        </select>
    </form>    
    <h2></h2>
</div>
