<?php
/*****************************************************************************************
ava.php                                   Hayward Peirce
This file contains the code pertaining to the registration of a new event
******************************************************************************************/
?>


<strong>Create a New Event: </strong>
<form action="member.php" method= "post">
	Event Name: <br><input type="text" name="event_name"><br>
	Event Date: (yyyy-mm-dd)<br><input type="text" name="event_date"><br>
	Event Description: <br><textarea rows="10" cols="30" method= "post" name="event_description"></textarea><br><br>
	Event Requirements: <br><textarea rows="10" cols="30" method= "post" name="event_requirements"></textarea>
	<br><input type="submit" value="Submit" >
</form>


<?php


//connect to the localhost server, and connect to the create_event database which contains the event and event comment details
if(mysql_connect('localhost', 'root', '') && mysql_select_db('create_event')){
	//if the all the event info variables are inputed and set
	if(isset($_POST['event_name'], $_POST['event_description'], $_POST['event_requirements'])){
		//set the inputted variables
		$event_name = mysql_real_escape_string(htmlentities($_POST['event_name']));
		$event_description = mysql_real_escape_string(htmlentities($_POST['event_description']));
		$event_requirements = mysql_real_escape_string(htmlentities($_POST['event_requirements']));
		$event_date = mysql_real_escape_string(htmlentities($_POST['event_date']));
		
		//if the variables are empty add an error message
		if(empty($event_name ) || empty($event_description) || empty($event_requirements)){
				$errors[]= 'All fields are required';
			}
		//if the diffrent fields exceed their character limits
		if(strlen($event_name)>25 || strlen($event_description)>255 || strlen($event_requirements)>255){
			$errors[]= 'One or more fields exceeded the character limit.';
		}
		//if there are no errors
		if(empty($errors)){
				//insert the new event into the events table
				$insert = "INSERT INTO `event` VALUES ('','$event_date','$event_name', '$event_description', '$event_requirements')";
				//if the event insertion worked redirect back to the member.php page
				if(mysql_query($insert)){
					header('Location: member.php');
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

?>