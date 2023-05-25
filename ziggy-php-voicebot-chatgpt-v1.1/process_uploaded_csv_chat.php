<?php
session_start();

// This code runs when the user uploads a saved chat (csv file).
// We use this code to convert the javascript message history to
// a php message history. PHP variables can only be changed on
// the server and not in the browser (like Javascript variables).

// phpVariable is the list of all chat messages (i.e. the chat history)

// If the list exists, create
if (isset($_POST['phpVariable'])) {
	
	// Create a messages list
	$_SESSION['message_history'] = $_POST['phpVariable'];
	

} else {
	
	// Assign the session variable to $messages
	//$messages = $_SESSION['message_history'];
	
}

?>