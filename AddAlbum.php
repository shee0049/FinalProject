<?php 
session_start();
require '/Common/Loggedin.php';
require 'Common/Validation.php';

if (!isset($_SESSION["username"])){
    header("Location: Login.php");
}

if ($_SERVER['REQUEST_METHOD']=="POST"){
    $errTitle = testInput($_POST["albumTitle"]);
    
}

?>

<div class="container">
    <h1>Create New Album</h1>
    <p>Welcome <?php echo $_SESSION["username"]; ?> not you?(Change user <a href="Login.php">here</a>)</p>
    
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <div class="form-group">
            <label>Title: </label>       
            <input type="text" class="form-control" name="albumTitle">
            <span class="error"><?php echo $errTitle;?></span>
        </div> 
        
     <div>
     <?php
     // opening the Database and reading the table accessibility
      require_once("dbconnection.php");
      $request = $dbConn->prepare('SELECT * FROM accessibility');
      $request->execute();
      $cpt=0;      
      $accCode = array();
      $accDesc = array();
      while ($data = $request->fetch())
       {           
        $accCode[$cpt] = $data['Accessibility_Code'];
        $accDesc[$cpt] = $data['Description'];
        $cpt++;
       }      
       
       $request->closeCursor();
       ?>
        <label>Accessibility:</label>  
       <select name="accessibility" id="accessibility" class="form-control">
        <?php 
        for ($i=0; $i<$cpt; $i++)
        {
         $accessibilityCode = $accCode[$i];
         $description       = $accDesc[$i];
        ?>
           
        <option value="<?php echo($accessibilityCode);?>">          
         <?php echo($description);?>
        </option>
        <?php
        }
        ?>
       </select>
    </div>
        
   <div class="form-group">
    <label>Description:</label>
    <textarea class="form-control" id="description" name="description" class="form-control" rows="8"></textarea>
   </div>

     <div>
     <input type="submit" value="Submit" class="btn btn-large btn-primary" />
     <input type="reset" value="Clear" class="btn btn-large btn-primary" />
     </div>    
        

    </form> 
</div>
<?php require '/Common/Footer.php';?>