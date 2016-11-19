<?php
/*****************************************************************************************
event_comment.php                                   Hayward Peirce
This file contains the code used to submit a new comment relating to a specific event
******************************************************************************************/


//connect to the localhost server, and connect to the create_event database which contains the event and event comment details
if(mysql_connect('localhost', 'root', '') && mysql_select_db('create_event')){
	//set the event id variable to know which event to link the comment to
	if(isset($_GET['pid'])){
		$eventid=$_GET['pid'];
			
	}
	
	//set the time and array for possible errors
	$time = time();
	$errors = array();
	
	//if the all the event comment info variables are inputed and set
	if(isset($_POST['comment_name'], $_POST['comment_email'], $_POST['comment_message'])){
		//set the inputted variables
		$comment_name = mysql_real_escape_string(htmlentities($_POST['comment_name']));
		$comment_email = mysql_real_escape_string(htmlentities($_POST['comment_email']));
		$comment_message = mysql_real_escape_string(htmlentities($_POST['comment_message']));
		
		//if the variables are empty add an error message
		if(empty($comment_name ) || empty($comment_email) || empty($comment_message)){
			$errors[]= 'All fields are required';
		}
		//if the diffrent fields exceed their character limits
		if(strlen($comment_name)>25 || strlen($comment_email)>255 || strlen($comment_message)>255){
			$errors[]= 'One or more fields exceeded the character limit.';
		}
		//add email validation code here
		
		//if there are no errors
		if(empty($errors)){
			echo $comment_name. '<br>';
			echo $comment_email .'<br>';
			echo $comment_message.' <br>';
			echo $eventid .'<br>';
			
			//insert comment into database
			$insert = "INSERT INTO `comments` VALUES ('','$eventid','$time','$comment_name','$comment_email','$comment_message')";
			
			//if the submission worked redirect back to form 
			if(mysql_query($insert)){
				echo 'made it this far';
				header("Location: member.php?pid=" . $eventid);
				
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
	
}
//if the page could not connect to the server
else{
	echo 'Could not connect';
}
?>
