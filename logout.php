<?php
/*****************************************************************************************
logout.php                                   Hayward Peirce
This file contains the code for ending a users session
******************************************************************************************/


//start the session so as to be able to modify the session
session_start();
//destroy the session so they are unable to see member pages
session_destroy();
//if a manual redirect is attempted link the user back to the login page
echo "You've been logged out! <a href='index.php'> Click here </a>to log back in.";
?>