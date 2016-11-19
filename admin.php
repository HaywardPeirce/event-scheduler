<?php
/*****************************************************************************************
admin.php                                   Hayward Peirce
This file displays user information for the admin to look and and modify
******************************************************************************************/


//start the session
session_start();
//is the user has admin privalages
if($_SESSION['admin']==1){
echo 'Welcome to the admin page!<br>';
echo '<a href="member.php">Return</a> to member page<br><br>';
//connect to the localhost server, and connect to the phplogin database which contains the user credentials
if(mysql_connect('localhost', 'root', '') && mysql_select_db('phplogin')){
	//set the default user list order type
	$order = 'date';
	//if the user changes the order style change the order variable
	if(isset($_GET['orderby'])){
			
			$orderby=$_GET['orderby'];
			if($orderby==2){
				$order= 'id';
			}
			else{
				$order= 'date';
			}
		}
	//if the admin has chicked to change a users admin status, and the two variable pass back to the page but the click are valid
	if(isset($_GET['userchange'], $_GET['userid'])){
		$userchange=$_GET['userchange'];
		$userid=$_GET['userid'];
		//if the user is being changed to an admin
		if($userchange==1){
			$userchange= 'id';
			//update the users row on the table
			$queryuserchange ="UPDATE `users` SET admin = '0' WHERE id = $userid ";
			//once updated redirect back to the admin page
			if(mysql_query($queryuserchange)){
				echo 'made it this far';
				header('Location: '.$_SERVER['PHP_SELF']);
			}
			
		}
		//if the user is geiing changed from admin to user
		else{
			//update the users row with the new admin value
			$queryuserchange ="UPDATE `users` SET admin = '1' WHERE id = $userid ";
			//once updated redirect back to the admin page
			if(mysql_query($queryuserchange)){
				echo 'made it this far';
				header('Location: '.$_SERVER['PHP_SELF']);
			}
		}
	}
	
	//query the table users for everything and order the data rows by the $order value, ie: date or userid
	$entries = mysql_query("SELECT * FROM `users` ORDER BY $order DESC");
	
	//if there are no users in the table
	if(mysql_num_rows($entries)==0){
		echo 'no entries yet';
	}
	//if there are users in the table
	else{
		echo '<h1><strong> <a href="admin.php"> Users: </a></strong></h1>';
		
		//code for the users information table heading row
		?>
		Order users by:
		<form name="userorderform">
		<select name="menu1">
		<option value="admin.php?orderby=1" selected>Date</option>
		<option value="admin.php?orderby=2">User ID</option>
		</select>
		<input type="button" name="Submit" value="Go" 
		onClick="top.location.href = this.form.menu1.options[this.form.menu1.selectedIndex].value;
		return false;">
		</form>
		
		
		<table border="1">
				<tr>
					<th width="150">
						<?php echo '<p> <strong>  --- Name: </strong>'; ?>
					</th>
					<th width="150">
						<?php echo '<p> <strong>  Username: </strong>'; ?>
					</th>
					<th width="150">
						<?php echo '<p> <strong>  Email: </strong>'; ?>
					</th>
					<th width="200">
						<?php echo '<p> <strong>  Join Date: </strong>'; ?>
					</th>
					<th width="120">
						<?php echo '<p> <strong>  Admin Status: </strong>'; ?>
					</th>
					<th width="60">
						<?php echo '<p> <strong>  Delete Account: </strong>'; ?>
					</th>
				</tr>
		</table>
		
		<?php
		//for as long as the queried value is true
		while($entries_row = mysql_fetch_assoc($entries)){
			//set the diffent user variables from the data from the table
			$entries_date = date("l d F Y", strtotime($entries_row['date']));
			$entries_id = $entries_row['id'];
			$entries_username= $entries_row['username'];
			$entries_admin= $entries_row['admin'];
			$entries_name= $entries_row['name'];
			$entries_email= $entries_row['email'];
			$_SESSION['eventchoice'] = $entries_id;
			
			//if the user is an admin change value to admin
			if($entries_admin==1){
				$entries_admin_name= 'Admin';
			}
			//otherwise change value to user
			else $entries_admin_name= 'User';
			
			//code for the users information table
			?>
			<table border="1">
				
				<tr>
					<td width="150">
						<?php echo $entries_name; ?>
					</td>
					
					<td width="150">
						<?php echo $entries_username; ?>
					</td>
						
					<td width="150">
						<?php echo $entries_email; ?>
					</td>
						
					<td width="200">
						<?php echo $entries_date ?>
					</td>
					
					<td width="120">
						<?php
						if($entries_username!=$_SESSION['username']){
							echo '<form action="admin.php?userchange='.$entries_admin.'&userid='.$entries_id.'" method="post">
								'.$entries_admin_name.'
								<input type="submit" value="Change" name="adminchange">
							</form>';
						}
						else echo $entries_admin_name;
						?>
					</td>
					<td width="60">
						<?php
						if($entries_username!=$_SESSION['username']){
							echo '<form action="admin.php?delete='.$entries_id.'" method="post">
								<input type="submit" value="Delete" name="delete">
							</form>';
						}
						?>

					</td>
				</tr>
			</table>
			
			<?php
			//id the admin has clicked the delete user button
			if(isset($_GET['delete'])){
				$deletesubmit = $_GET['delete'];
				//delete the user
				$delete = "DELETE FROM `users` WHERE `id`='$deletesubmit'";
				//if the delete worked redirect the user back to the admin page
				if(mysql_query($delete)){
					echo 'this event has been deleted';
					header('Location: admin.php');
				}
			}			
			
		}
	}
}


}
//if a manual acces to the page is attemped and the user has no admin privilages, redirect them back to the member page 
else{
echo 'you do not have admin privilages <a href="member.php"> Return to member page: </a>';
}