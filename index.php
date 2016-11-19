<?php
/*****************************************************************************************
index.php                                   Hayward Peirce
This file displays login information
******************************************************************************************/
?>

<form action="login.php" method= "post">
	Please Log In using your Lisgar OCDSB Login Credentials<br>
	Username: <br><input type="text" name="username"><br>
	Password: <br><input type="password" name="password"><br>
	<input type="submit" value="Log in" > 
	<a href='register.php'>Register</a>
	
</form>

<br><br>

<strong>Feedback:</strong>

<hr>
<?php include("feedback.php"); ?>

