<?php 
session_start();
require '/Common/Loggedin.php';
require 'Common/Validation.php';
require 'dbconnection.php';

if (!isset($_SESSION["username"])){
    header("Location: Login.php");
}

           
    
if ($_SERVER['REQUEST_METHOD']=="POST"){    
    $errTitle = testInput($_POST["albumTitle"]);
    
    if (!$errTitle){
        echo "<h1>Album succesfully added!</h1>";
    }
    
    if ($errTitle == ""){
        $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);        
        date_default_timezone_set("America/Toronto");        
        $title 	        = mysql_real_escape_string($_POST['albumTitle']);
        $accessibility  = mysql_real_escape_string($_POST['accessibility']);	
        $description	= mysql_real_escape_string($_POST['description']);
        $date 		= mysql_real_escape_string(date('Y-m-d'));
        $userId 	= mysql_real_escape_string($_SESSION['username']);

        // Set Prepared Sql statement
        $sql = "INSERT INTO Album (Title, Description, Date_Updated, Owner_id, Accessibility_Code) VALUES(?,?,?,?,?)";

        // bind sql to database connection
        $stmt = mysqli_prepare($link, $sql);
        // bind parameters to the sql statement ?'s
        mysqli_stmt_bind_param($stmt, 'sssss', $a, $b, $c, $d, $e);
        // set parameters to variables
        $a = $title;
        $b = $description;
        $c = $date;
        $d = $userId;
        $e = $accessibility;
        
        //execute sql statement
        if(mysqli_stmt_execute($stmt)){
            // statement is successful, display success msg
        }else {
            // display msg that it failed.
        }
        mysqli_close($link);
    }    
}

?>

<div class="container">
    <h1>Create New Album</h1>
    <p>Welcome <?php echo $_SESSION["name"]; ?> not you?(Change user <a href="Login.php">here</a>)</p>
    
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
