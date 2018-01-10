<?php
session_start();
require '/Common/Loggedin.php';
require '/Common/Validation.php';

if (!isset($_SESSION["username"])){
    header("Location: Login.php");
}
$friend = $_GET['userid'];
?>

<div class="container">
    <h1><?php echo "$friend's Pictures" ?></h1>
</div>



<?php require '/Common/Footer.php'; ?>