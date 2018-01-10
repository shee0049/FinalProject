<?php 
session_start();
require '/Common/Loggedin.php'; 
require 'dbconnection.php';
include '/Common/Constants.php';

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


if ($_POST["btnSubmit"] == "Submit") 
{
    for($i = 0; $i < count($_FILES["boxUpload"]["tmp_name"]); $i++){
	if ($_FILES['boxUpload']['error'][$i] == 0)
	{ 	
            $filePath = Save_Uploaded_Files(ORIGINAL_IMAGE_DESTINATION, $i);
            $imageDetails = getimagesize($filePath);
            
            if ($imageDetails && in_array($imageDetails[2], $supportedImageTypes))
            {
                resamplePictures($filePath, IMAGE_DESTINATION, IMAGE_MAX_WIDTH, IMAGE_MAX_HEIGHT);	
                resamplePictures($filePath, THUMBNAIL_DESTINATION, THUMBNAIL_MAX_WIDTH, THUMBNAIL_MAX_HEIGHT);
            }
            else
            {
                $error = "Uploaded file is not a supported type"; 
                unlink($filePath);
            }
	}
	elseif ($_FILES['boxUpload']['error'][$i] == 1)
	{
		$error = "Upload file is too large"; 
	}
	elseif ($_FILES['boxUpload']['error'][$i] == 4)
	{
		$error = "No upload file specified"; 
	}
	else
	{
            $error  = "Error happened while uploading the file. Try again late"; 
	}
    }
}


?>
<input type="submit" value="" disabled="disabled" />
<div class="container">
    <h1>Upload Pictures</h1>
    <p>Accepted File formats: JPG, GIF and PNG</p>  
    <p>You can upload multiple pictures at a time by pressing the shift key while selecting pictures.</p>
    <p>When uploading multiple pictures the title and description fields will be applied to all pictures.</p>

    <br>    
    <form id="form1" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST">
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
            <textarea class="form-control" id="description" name="description" rows="8"></textarea>
        </div>
        <input type="submit" class="btn btn-primary" name="btnSubmit" value="Submit">
        <br>
        <br>
    </form>
</div>
<script>
    $('#albumTitle').on('change', function() {
        $('#form1').submit();
    });
</script>
<?php require '/Common/Footer.php';?>