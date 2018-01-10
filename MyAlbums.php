<?php
session_start();

if (!isset($_SESSION["username"])){
    header("Location: Login.php");
}
require 'Common/Loggedin.php';


?>

   <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
<div class="container">
    <h1 class="center">My Albums</h1>
    <p>Welcome <?php echo $_SESSION["name"]?>! (not you? change user <a href="Login.php">here!</a>)</p>
    <p><a href="AddAlbum.php">Create a New Album</a></p>
    <table class="table">      
        <tr>
            <th>Title</th>
            <th>Date Updated</th>
            <th>Number of Pictures</th>
            <th>Accessibility</th>
        </tr>
        
  <?php
  
  $con = mysqli_connect("localhost","PHPSCRIPT","1234","CST8257") or die ("Error connection");
  
  $query = "SELECT * FROM Album";
  
  $result = mysqli_query($con, $query);
  
  while($row = mysqli_fetch_array($result)){
      echo "<tr>
      <". "td>".$row["Title"]."</td>
      <". "td>".$row["Date_Updated"]."</td>
      <". "td>".$row[""]."</td>
      <". "td>".$row["Accessibility_Code"]."</td>

     </tr>";
  }
  
  ?>
        

    </table>
</div>  
   
  </form> 

  
<?php require 'Common/Footer.php' ;?>



