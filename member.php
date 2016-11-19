<?php
/*****************************************************************************************
member.php                                   Hayward Peirce
This file displays main event, comment, and event creation code
******************************************************************************************/


//start the session
session_start();
//if the session username is set
if($_SESSION['username']){
	//welcome the user and link them to the logout page
	echo "Welcome, ". $_SESSION['username']. "! <br><a href='logout.php'>Logout</a>";
	echo "<hr>";
	
	
	
	?>

	<table border="1">
	<tr>
	  <td width="600">

	<?php
	//connect to the localhost server, and connect to the create_event database which contains the event and event comment details
	if(mysql_connect('localhost', 'root', '') && mysql_select_db('create_event')){
		//display entries
		//if the user has clicked on an event and set that event choice id set that as a session variable
		if(isset($_GET['pid'])){
			$eventid=$_GET['pid'];
			$_SESSION['pid']= $eventid;
		}
		
		//code for printing out the list of upcoming events
		
		//query the database for the info related to the events: name, time, eventid; and order by time
		$entries = mysql_query("SELECT `eventname`, `time`, `eventid` FROM `event` ORDER BY `time` DESC");
		//if there are no events tell the user so
		if(mysql_num_rows($entries)==0){
			echo 'no entries yet';
		}
		//if there are events:
		else{
			echo '<h1><strong> <a href="member.php"> Upcoming Events: </a></strong></h1>';
			if($_SESSION['admin']==1){
				echo '<a href="admin.php"> Admin page: </a> ';
			}
			
			//sycling through the rows:
			while($entries_row = mysql_fetch_assoc($entries)){
				//get the event info from the database
				$entries_time = date("l d F Y",strtotime($entries_row['time']));
				$entries_id = $entries_row['eventid'];
				$entries_name= $entries_row['eventname'];
				$_SESSION['eventchoice'] = $entries_id;
				
				
				//code for the event list
				//if the user has admin privilages
				if($_SESSION['admin']==1){
					//table for displaying the list of events
					?>
					<table>
						<tr>
							<td>
							<?php echo '<p> <strong>  --- <a href="member.php?pid='.$entries_id.'"> '.$entries_name.' </a> ('.$entries_time.') </strong>';
							echo '</p>';
							?>
							</td>
							<td>
							<form action="member.php?delete=<?php echo $entries_id; ?>" method="post">
								<input type="submit" value="Delete" name="delete">
							</form>
							</td>
						</tr>
					</table>
					
					<?php
					//if the admin has clicked the delete button
					if(isset($_GET['delete'])){
						//set the delete variable from the earlier created delete session variable
						$deletesubmit = $_GET['delete'];
						$delete = "DELETE FROM `event` WHERE `eventid`='$deletesubmit'";
						//if the deletion works redirect back to the member.php page
						if(mysql_query($delete)){
							echo 'this event has been deleted';
							header('Location: member.php');
						}
					}			
				}
				//if the user has no admin privalages display the event list without the delete button
				else {
					echo '<p> <strong>  --- <a href="member.php?pid='.$entries_id.'"> '.$entries_name.' </a> ('.$entries_time.') </strong></p> ';
				}
			}
			
		}
		
		
		
			if(isset($_GET['pid'])){
				
				//code for printing out the details of a specific event
				//echo '<strong>Event Details:</strong>';
				$entries_full = mysql_query("SELECT `time`, `eventname`, `description`, `requirements` FROM `event` WHERE `eventid`='$eventid' ");
				
				//if there are no events
				if(mysql_num_rows($entries_full)==0){
					echo 'no event yet';
				}
				//if there are events
				else{
					//keeping the list refreshed, updating the entries from the queried table
					while($entries_full_row = mysql_fetch_assoc($entries_full)){
						$entries_full_time = date("l d F Y",strtotime($entries_full_row['time']));
						$entries_full_name= $entries_full_row['eventname'];
						$entries_full_description= $entries_full_row['description'];
						$entries_full_requirements= $entries_full_row['requirements'];
						
						echo'<hr>';
						
						//write the entries to the page
						echo '<p><strong> '.$entries_full_name.' ('.$entries_full_time.')</strong><br>';
						echo '<strong>Description: </strong>'.$entries_full_description.'<br><strong> Requirements: </strong>'.$entries_full_requirements.'<br></p>';
					}
				}
			
				//code for the comments on a specific event
				echo '<strong>Responses:</strong>';
				//query the comments table for the comments related to the event with event id $eventid
				$comment_entries = mysql_query("SELECT `timestamp`, `comment_name`, `comment_email`, `comment_message`, `id` FROM `comments` WHERE `eventid`='$eventid' ORDER BY `timestamp` DESC");
				
				//if there are no comments
				if(mysql_num_rows($comment_entries)==0){
					echo ' No entries yet';
				}
				//if there are comments
				else{
					//keeping the list refreshed, updating the entries from the queried table
					while($comment_entries_row = mysql_fetch_assoc($comment_entries)){
						$comment_entries_timestamp = date('D M Y @ h:i:s', $comment_entries_row['timestamp']);
						$comment_entries_id= $comment_entries_row['id'];
						$comment_entries_name= $comment_entries_row['comment_name'];
						$comment_entries_email= $comment_entries_row['comment_email'];
						$comment_entries_message= $comment_entries_row['comment_message'];
						
						
						//if the user has admin privilages 
						if($_SESSION['admin']==1){
						$entries_id=$_GET['pid'];
						
						//comments table with delete button if the user is an admin
						?>
						<table>
							<tr>
								<td>
								<?php echo '<p><strong>Posted by '.$comment_entries_name.' ('.$comment_entries_email.') on '.$comment_entries_timestamp.'</strong>:<br>'.$comment_entries_message.' </p>';
								echo '</p>';
								//echo 'entriesid: '.$entries_id;
								?>
								</td>
								<td>
								<form action="member.php?pid=<?php echo $entries_id; ?>&deletecom=<?php echo $comment_entries_id; ?>" method="post">
									<input type="submit" value="Delete" name="delete">
								</form>
								</td>
							</tr>
							<tr>
							</tr>
						</table>
						
						<?php
						//if the delete comment button has been pressed
						if(isset($_GET['deletecom'])){
							$deletecomsubmit = $_GET['deletecom'];
							//delete the comment with a comment id of $deletecomsubmit
							$deletecomquery = "DELETE FROM `comments` WHERE `id`='$deletecomsubmit'";
							//if the deletion worked redirect the user back to the member page with the apended event id they were just looking at
							if(mysql_query($deletecomquery)){
								echo 'this comment has been deleted';
								header("Location: member.php?pid=" . $entries_id);
								
							}
						}			
						}
						//id the user is not an admin
						else {
							echo '<p><strong>Posted by '.$comment_entries_name.' ('.$comment_entries_email.') on '.$comment_entries_timestamp.'</strong>:<br>'.$comment_entries_message.' </p>';
						}
					}
				}
				
				//code for the event comment box
				//$_SESSION['pid']= $eventid;
				//include("event_comment.php");
				$_SESSION['pid']= $eventid;
				//echo 'event id:'.$eventid;
				$username= $_SESSION['username'];
				//$email= $_SESSION['email'];
				
				//form for the submition of a new event comment
				?>
				<hr>
				<form action="event_comment.php?pid=<?php echo $eventid; ?>" method="post">
					<b>Submit your comment:</b><br>
					Name: <br><input type="text" name="comment_name" value="<?php echo $username; ?>"maxlength="25"><br>
					Email:<br><input type="text" name="comment_email" maxlength="255"><br>
					Comments: <br><textarea rows="10" cols="40" method= "post" name="comment_message"></textarea><br><br>
					<input type="submit" value="Submit comment">
				</form>

				<?php
				
			}
		}
	
	?>

	<td width="600">
	<?php include("ava.php"); ?>

	</tr>
	</table>


<?php
	
}
//if you are not within a session kill the page 	
else die("You must be logged in!")
?>

<hr>