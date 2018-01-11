<?php
session_start();

if (!isset($_SESSION["username"])){
    header("Location: Login.php");
}
require 'Common/Loggedin.php';
require 'dbconnection.php';
$user = $_SESSION["username"];
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($_GET["action"] == "delete"){
    $albumid = $_GET["albumid"];
    
    $sql = "DELETE FROM Album WHERE Album_id=?";
    
    $stmt = mysqli_prepare($link, $sql);
    
    mysqli_stmt_bind_param($stmt, 's', $u);
    
    $u = $albumid;
    
    if(mysqli_stmt_execute($stmt)){
        mysqli_stmt_close($stmt);        
        header("Location: MyAlbums.php");
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    
    $values = $_POST["select"];
    
    foreach($values as $value){
        $split = explode(" ", $value);
        
        $access = $split[0];
        $albumid = $split[1];
        
        $sql = "UPDATE Album SET Accessibility_Code=? WHERE Album_id=?";
        
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, 'ss', $a, $u);        
        $a = $access;
        $u = $albumid;
        if (mysqli_stmt_execute($stmt)){
            $msg = "Album was changed to $a";            
        }
        else {
            $msg="There was a problem processing your request.";
        }         
    }
}
?>

   <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
<div class="container">
    <h1 class="center">My Albums</h1>
    <p>Welcome <?php echo $_SESSION["name"]?>! (not you? change user <a href="Login.php">here!</a>)</p>
    <p><a href="AddAlbum.php">Create a New Album</a></p>
    <span class="error"><?php echo $msg;?></span>
    <table class="table">      
        <tr>
            <th>Title</th>
            <th>Date Updated</th>
            <th>Number of Pictures</th>
            <th>Accessibility</th>
            <th></th>
        </tr>
        
  <?php
    $sql = "SELECT Album_id, Title, Date_Updated, Accessibility_Code FROM Album WHERE Owner_Id=?";
  
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 's', $u);
    $u = $user;
    $titles = array();
    if (mysqli_stmt_execute($stmt)){
        mysqli_stmt_bind_result($stmt,$id, $title, $date, $access);
        while (mysqli_stmt_fetch($stmt)){
            echo "<tr><td><a href='MyPictures.php?albumid=$id'>$title</a></td><td>$date</td><td>PLACEHOLDER</td>";
            if ($access == "shared"){
                echo "<td><select class='form-control' name='select[]'><option value='shared $id'>Accessibile by the owner and friends</option><option value='private $id'>Accessible only by the owner</option></select></td><td><a class='delete' href='MyAlbums.php?action=delete&albumid=$id'>Delete</a></td></tr>";
            }
            else {
                echo "<td><select class='form-control' name='select[]'><option value='private $id'>Accessible only by the owner</option><option value='shared $id'>Accessibile by the owner and friends</option></select></td><td><a class='delete' href='MyAlbums.php?action=delete&albumid=$id'>Delete</a></td></tr>";
            }
        }
    }
  
  ?>
    </table>
    <input type="submit" class="btn btn-primary" value="submit">
</div>  
   
  </form> 

  
<?php require 'Common/Footer.php' ;?>



