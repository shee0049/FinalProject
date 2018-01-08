<?php require 'Common/Header.php'; ?>

<div class="container">
    <h1>Add Friend</h1>
    <p>Welcome <!-- add name --> (not you? change user <a href="Login.php">here</a>) </p><br>
    <p>Enter the ID of the user you want to add.</p>
    
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <div class="form-group"> 
            <label for="studentID">User Name:</label>
            <input type="text" class="form-control" id="studentID" name="studentID">
        </div> 
        <button type="submit" class="btn btn-primary">Send Friend Request</button>
    </form>
</div>

<?php require 'Common/Footer.php'; ?>
