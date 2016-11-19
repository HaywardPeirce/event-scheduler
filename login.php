<?php
/*****************************************************************************************
login.php                                   Hayward Peirce
This file contains the login code
******************************************************************************************/


//start the users login session
session_start();

//get the enetered login credentials from the form on index.php
$username = $_POST['username'];
$password = $_POST['password'];

//if the user has inputted both a username and password
if($username&&$password){
	//connect to the localhost server, else the page dies with a counldn't connect message
	$connect= mysql_connect("localhost", "root", "") or die("couldn't connect to server");
	//connect to the phplogin database which contains the user credentials
	mysql_select_db("phplogin") or die("couldnt find DB");
	//query the server getting all users login info
	$query= mysql_query("SELECT * FROM users WHERE username='$username'");
	$numrows= mysql_num_rows($query);
	//if there are users in the database
	if($numrows!=0){
		//while array row = the assosiative array from the query
		while($row = mysql_fetch_assoc($query)){
			//set the database set of login credentials
			$dbusername= $row['username'];
			$dbpassword= $row['password'];
			$dbemail= $row['email'];
		}
		
		//check if the credentials the user inputted match with those in the database
		if($username==$dbusername&&md5($password)==$dbpassword){
			//set the session variables, these allow only a loged in user to see the member pages
			$_SESSION['username'] = $username;
			$_SESSION['email'] = $dbemail;
			//get everything form the users table in the column with the username $username
			$get = mysql_query("SELECT * FROM `users` WHERE username ='$username'");
			if($row = mysql_fetch_assoc($get)){
				//set the admin session variable to the value at admin
				$admin = $row['admin'];
				$_SESSION['admin'] = $admin; 
			}
			//redirect the user on to the member page as they have pass the login test
			header('Location: member.php');
		}
		//if the user passwords do not match
		else echo "Incorrect password";
	}
	//if the username is not in the database
	else die("That user doesnt exist");
	
}
//if the user did not enter all the rewuired login credentials
else 
	die("Please enter a username and password");
	
?>