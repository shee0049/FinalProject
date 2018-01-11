<?php 
session_start();
require '/Common/Loggedin.php'; 
require 'dbconnection.php';
require '/Common/Validation.php';
include '/Common/Constants.php';

if (!isset($_SESSION["username"])){
    header("Location: Login.php");
}
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
$user = mysql_real_escape_string($_SESSION["username"]);

$sql = "SELECT Title, Album_Id FROM Album where Owner_Id=?"; 
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, 's', $u);
$u = $user;
$titles = array();
$ids = array();
if (mysqli_stmt_execute($stmt)){
    mysqli_stmt_bind_result($stmt, $title, $id);
    while (mysqli_stmt_fetch($stmt)){
        array_push($titles, $title);
        array_push($ids, $id);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST"){ 
    $err = testInput($_POST["pictureTitle"]);
    if(isset($_FILES['image'])){
        if(isset($_POST["album"]) && $err == "") {         
            $errors= array();
            $id = $_POST["album"];
            $title = $_POST["pictureTitle"];
            $desc = $_POST["description"];
            $date = date('Y-m-d');
            $file_name = $_FILES['image']['name'];
            $file_size =$_FILES['image']['size'];
            $file_tmp =$_FILES['image']['tmp_name'];
            $file_type=$_FILES['image']['type'];
            $file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));

            $expensions= array("jpeg","jpg","png");

            if(in_array($file_ext,$expensions)=== false){
               $err="extension not allowed, please choose a JPEG or PNG file.";
            }

            if($file_size > 2097152){
               $err='File size must be excately 2 MB';
            }

            if(empty($err)==true){                
               $file_name = "$user-$id-$title.".$file_ext;
               if(move_uploaded_file($file_tmp,"Pictures/".$file_name)){
                    $sql = "INSERT INTO Picture (Album_Id,FileName,Title,Description,Date_Added) VALUES (?,?,?,?,?)";
                    $stmt = mysqli_prepare($link, $sql);
                    mysqli_stmt_bind_param($stmt, 'sssss', $a,$b,$c,$d,$e);
                    $a = $id;
                    $b = $file_name;
                    $c = $title;
                    $d = $description;
                    $e = $date;
                    mysqli_execute($stmt);
               }
            }
        }           
   }
}



/*
if (isset($_POST["btnSubmit"])) 
{
    $destination = './Pictures';
    $original = './Pictures/OriginalPictures';
    $album = './Pictures/AlbumPictures';
    $thumbnails = './Pictures/AlbumThumbnails';

    if (!file_exists($destination))
    {
        mkdir($destination);
        mkdir($original);
        mkdir($album);
        mkdir($thumbnails);
    }
    
    
    
    for($i = 0; $i < count($_FILES["files"]["tmp_name"]); $i++){
	if ($_FILES['files']['error'][$i] == 0)
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
            if (move_uploaded_file($_FILES["files"]["tmp_name"], $destination)){
                echo "file uploaded";
            }
	}
	elseif ($_FILES['files']['error'][$i] == 1)
	{
		$error = "Upload file is too large"; 
	}
	elseif ($_FILES['files']['error'][$i] == 4)
	{
		$error = "No upload file specified"; 
	}
	else
	{
            $error  = "Error happened while uploading the file. Try again late"; 
	}
    }
}*/


?>

<div class="container">
    <h1>Upload Pictures</h1>
    <p>Accepted File formats: JPG, GIF and PNG</p>  
    <p>You can upload multiple pictures at a time by pressing the shift key while selecting pictures.</p>
    <p>When uploading multiple pictures the title and description fields will be applied to all pictures.</p>

    <br>    
    <form id="form1" action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="album">Upload to Album:</label>
            <select name="album" class="form-control">
            <?php 
                $i=0;
                foreach ($titles as $t){                    
                    echo "<option value='$id[$i]'>$t</option>";                 
                    $i++;
                }
            ?>
            </select>
        </div>    
        <div class="form-group">
            <label for="selectedFiles">File to upload:</label>
            <input type="file" class="form-control" id="selectedFiles" name="image[]" accept="image/*" multiple size="40">
        </div>        
        <div class="form-group">
            <label for="pictureTitle">Title:</label>
            <input type="text" class="form-control" name="pictureTitle">
        </div>
        <span class="error"><?php echo $err ?></span>
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
