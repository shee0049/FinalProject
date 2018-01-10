<?php 
session_start();
require '/Common/Loggedin.php'; 
require 'dbconnection.php';

if (!isset($_SESSION["username"])){
    header("Location: Login.php");
}
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
$user = mysql_real_escape_string($_SESSION["username"]);

$sql = "SELECT Title FROM Album where Owner_Id=?"; 
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, 's', $u);
$u = $user;
$titles = array();
if (mysqli_stmt_execute($stmt)){
    mysqli_stmt_bind_result($stmt, $title);
    while (mysqli_stmt_fetch($stmt)){
        array_push($titles, $title);
    }
}
?>
<div class="container">
    <h1>Upload Pictures</h1>
    <p>Accepted File formats: JPG, GIF and PNG</p>  
    <p>You can upload multiple pictures at a time by pressing the shift key while selecting pictures.</p>
    <p>When uploading multiple pictures the title and description fields will be applied to all pictures.</p>

    <br>    
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST">
        <div class="form-group">
            <label for="albumTitle">Upload to Album:</label>
            <select name="albumTitle" class="form-control">
            <?php 
                foreach ($titles as $t){
                 echo "<option value='$t'>$t</option>";
                }
            ?>
            </select>
        </div>    
        <div class="form-group">
            <label for="selectedFiles">File to upload:</label>
            <input type="file" class="form-control" id="selectedFiles" name="files[]" multiple="multiple" accept="image/x-png,image/gif,image/jpeg">
        </div>
        <div class="form-group">
            <label for="pictureTitle">Title:</label>
            <input type="text" class="form-control" name="pictureTitle">
        </div>
        <div class="form-group">
            <label for="pictureDescription">Description: </label>
            <textarea class="form-control" id="description" name="description" class="form-control" rows="8"></textarea>
        </div>
        <input type="submit" class="btn btn-primary" name="submit" value="Susbmit">
        <br>
        <br>
    </form>
</div>
<?php require '/Common/Footer.php';?>