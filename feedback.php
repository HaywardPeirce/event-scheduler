<?php
/*****************************************************************************************
feedback.php                                   Hayward Peirce
This file displays and contains the code for the main page comment box
******************************************************************************************/


//connect to the localhost server, and connect to the feedback database which contains the loginpage comment details
if(mysql_connect('localhost', 'root', '') && mysql_select_db('feedback')){
	//set the time and array for possible errors
	$time = time();
	$errors = array();
	
	//if the all the comment info variables are inputed and set
	if(isset($_POST['feedback_name'], $_POST['feedback_email'], $_POST['feedback_message'])){
		//set the inputted variables
		$feedback_name = mysql_real_escape_string(htmlentities($_POST['feedback_name']));
		$feedback_email = mysql_real_escape_string(htmlentities($_POST['feedback_email']));
		$feedback_message = mysql_real_escape_string(htmlentities($_POST['feedback_message']));
		
		//if the variables are empty add an error message
		if(empty($feedback_name ) || empty($feedback_email) || empty($feedback_message)){
			$errors[]= 'All fields are required';
		}
		//if the diffrent fields exceed their character limits
		if(strlen($feedback_name)>25 || strlen($feedback_email)>255 || strlen($feedback_message)>255){
			$errors[]= 'One or more fields exceeded the character limit.';
		}
		//add email validation code here
		
		//if there are no errors
		if(empty($errors)){
			//insert comment into database
			$insert = "INSERT INTO `entries` VALUES ('','$time','$feedback_name','$feedback_email','$feedback_message')";
			//if the submission worked, redirect back to the page
			if(mysql_query($insert)){
				header('Location: '.$_SERVER['PHP_SELF']);
			}
			//if the submition dod not work, add an error
			else{
				$errors[]= 'Something went wrong, please try again.';
			}
			
		}
		//if there are error, loop through the error array displaying all the errors
		else{
			foreach($errors as $error){
				echo '<p><strong>'.$error.'</strong></p>';
			}
		}
	}
	
	//display entries
	//query the database for the comment info
	$entries = mysql_query("SELECT `timestamp`, `name`, `email`, `message` FROM `entries` ORDER BY `timestamp` DESC");
	
	//if there are no comments
	if(mysql_num_rows($entries)==0){
		echo 'no entries yet';
	}
	//if there are comments
	else{
		//keeping the list refreshed, updating the entries from the queried table
		while($entries_row = mysql_fetch_assoc($entries)){
			$entries_timestamp = date('D M Y @ h:i:s', $entries_row['timestamp']);
			$entries_name= $entries_row['name'];
			$entries_email= $entries_row['email'];
			$entries_message= $entries_row['message'];
			//write the entries to the page
			echo '<p><strong>Posted by '.$entries_name.' ('.$entries_email.') on '.$entries_timestamp.'</strong>:<br>'.$entries_message.' </p>';
		}
	}
		
}
//if the page could not connect to the server
else{
	echo 'Could not connect';
}
?>

<hr>
<form action="<?php echo  htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
	<b>Submit your Feedback:</b><br>
	Name: <br><input type="text" name="feedback_name" maxlength="25"><br>
	Email:<br><input type="text" name="feedback_email" maxlength="255"><br>
	Comments: <br><textarea rows="10" cols="40" method= "post" name="feedback_message"></textarea><br><br>
	<input type="submit" value="Submit Feedback">
</form>