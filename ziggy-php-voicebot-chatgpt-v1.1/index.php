<?php
session_start();

include "name_config.php";

//echo $bot_name;

?>


<!-- 
https://pixabay.com/vectors/monster-pacman-pac-man-alien-eye-148798/
-->

<!DOCTYPE html>
<html lang="en">

	<head>
	
	<meta charset="utf-8">
	<title>Ziggy PHP Voicebot</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="A php voicebot powered by ChatGPT.">
		
		
	<!--CSS Stylesheets-->
	<link rel="stylesheet" href="css/w3.css">
	
	<link rel="shortcut icon" type="image/png" href="assets/ziggy.png">
	
	
    <style>
      body {
        background-color: #f9f9f9;
		font-family: Arial, sans-serif;
		font-size: 18px;
		color: #36454F;
      }
	   main {
	   	margin-bottom: 200px;
	   	color: #36454F;
        padding: 10px;
	}
	
	.responsive {
		 width: 100%; /*Makes media scalable as the viewport size changes*/
		 height: auto;
		 max-width: 100px;
		 
		 } 
      .container {
        width: 100%;
        max-width: 600px;
        margin: 0 auto;
        padding: 0 20px;
      }
	  
      .sticky-bar {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background-color: #36454F; /* Charcoal */
        color: #fff;
        padding: 10px; /*30px*/
        text-align: center;
      }
      .sticky-bar input[type="text"] {
        padding: 10px;
        border-radius: 5px;
        border: none;
        margin-right: 10px;
        width: 60%;
        font-size: 18px;
      }
      .sticky-bar input[type="submit"] {
        background-color: #fff;
        color: #333;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        margin-left: 10px;
      }
	  .message-container {
        margin-bottom: 10px;
        padding: 5px 20px;
        background-color: #f0f0f0;
        border-radius: 5px;
		line-height: 1.8;
		letter-spacing: 0.02em;
	}
	.set-color1 {
		color: red;
	}
	.set-color2 {
		color: purple;
	}
	
	
	#chat-buttons {
	  display: flex;
	  justify-content: center;
	  align-items: center;
	  margin-top: 10px;
	}
	
	#chat-buttons button {
	  margin-right: 20px;
	  padding: 0px 20px;
	  border-radius: 5px;
	  cursor: pointer;
	  font-size: 15px;
	  background-color: #36454F;
	  color: #f9f9f9;
	  border: none;
	}
	
	#chat-buttons input[type="file"] {
	  display: none;
	}
	
	#chat-buttons label {
	  display: inline-block;
	  padding: 0px 20px;
	  border-radius: 5px;
	  cursor: pointer;
	  font-size: 15px;
	  background-color: #36454F;
	  color: #f9f9f9;
	  border: none;
	}
		
	#chat-buttons input[type="file"] + label {
	  margin-right: 10px;
	}
	
	#chat-buttons input[type="file"] + label:before {
	  content: "Load a saved chat";
	}
	
	.sticky-image {
			position: fixed;
			top: 0;
			left: 0;
		}

	
	</style>

	
  </head>
  <body>
	  
	  
	  
	  
    <div class="container w3-animate-opacity">
		
		<!-- -->
		<div id="main-image">
			<img class="responsive" src="assets/ziggy.png" alt="Avatar">
		</div>
		
	
	  
	  <main id="chat" class="texts">
	      <div class="message-container">
			  <span id="first-chat-block" class="set-color1"><b>&#x2022 ChatGPT</b></span>
	        
			 <p>Hi there, I'm Ziggy, your personal chat companion.<br>
				 I respond using both text and voice. Please ensure that your sound is not muted.</p>
			 <p>To have a voice conversation, first click "Start Voicechat", then allow access to your mic and then... just say hello.</p>
			 
			 <p>Please note: Voicechat works well on Mac, but it's unstable on Windows and on Android.</p>
	        
	      </div>
		  
	      <!-- Add more message containers here -->
		  
		   <!-- The div for the spinner gets
		  added and deleted here. -->
 	 </main>
	 
	 
	 
	 
	 
	 
      <div class="sticky-bar">
		  
		<form id="myForm" action="chatgpt-api-code.php" method="post">
          <input id="user-input" type="text" name="my_message" placeholder="Send a message..."  autofocus>
		  <input type="hidden" name="robotblock">
		  <input id="submit-btn" type="submit" value="Send">
	  </form>
		
		
		<div id="chat-buttons">
		
		  <button onclick="saveChatHistoryToCsv()">Save this chat</button>
		  <input type="file" id="csv-file" accept=".csv">
		  <label for="csv-file"></label>
	  		
		  
		  <button onclick="start_recog(submit_text_to_php)">Start Voicechat</button>
		  
		  <!-- This mutes the bot when it's talking. -->
		  <button onclick="quiet_please()">Quiet please</button>
		</div>
		
      </div>
	 
    </div>
	
	
	<!--The page gets scrolled up to this id.-->
	<div id="chatgpt">
	</div>
	
	<!--Onload a click is simulated on this to scroll the page to id="bottom-bar"-->
	<a href="#chatgpt" id="scroll-page-up"></a>
	<a href="#test100" id="scroll-to-last-message"></a>
	
	
  </body>
</html>




<script>
  
//Simulates a click.
function simulateClick(tabID) {
	
	// Simulate a click.
	document.getElementById(tabID).click();
	
}

</script>


<!-- Import the utils.js file -->
<script src="utils.js"></script>

<!-- Hosting JQuery locally because this desktop app
 must be able to run offline. -->
<script src="jquery/jquery-3.6.1.min.js">
</script>



<script>
	
// These names are set in name_config.php
// That file has been included at the top of this page.
const bot_name = "<?php echo $bot_name; ?>";
const user_name = "<?php echo $user_name; ?>";



// Remove these suffixes. I think removing them makes the chat sound more natural.
// They will sliced off the bot's responses.
// This is done below in the 'Remove suffixes' part of the code.
var suffixes_list = ['How can I help you?', 'How can I assist you today?', 'How can I help you today?', 'Is there anything else you would like to chat about?', 'Is there anything else I can assist you with today?', 'Is there anything I can help you with today?', 'Is there anything else you would like to chat about today?', 'Is there anything else I can assist you with?', 'Is there anything else I can help you with?'];

</script>


<script>
	// Set the name of the bot in the first chat block
	document.getElementById("first-chat-block").innerHTML = "<b>&#x2022 " + bot_name + "</b>";
</script>


<script>
	
// This code is triggered when the user uploads a csv file
// that contains a saved chat.
const fileInput = document.getElementById("csv-file");

fileInput.addEventListener("change", function(event) {
	
  const file = event.target.files[0];
  
  loadChatHistoryFromCsv(file);
});

</script>


<script>
	
var message_history;

	
// PHP Ajax Code
/////////////////
	
var form = document.getElementById('myForm');

form.onsubmit = function(event) {
	
	
  // Prevent the default form submission behavior
  event.preventDefault();
  // Get the form data
  var formData = new FormData(form);
  
  // Clear the form input
  form.reset();
  
  // Get the value of my_message
  var $my_message = formData.get("my_message");
  //console.log($my_message);
  
  // Format the input into paragraphs. This
  // adds paragrah html to the students chat.
  // It's main use is in Maiya's chat where the long response needs 
  // to be formatted into separate paragraphs.
  $my_message = formatResponse($my_message);

  
  var input_message = {
  sender: user_name,
  text: $my_message
	};
	
	
	console.log(input_message.text);
	
	
	// Add a user message to the chat
	addMessageToChat(input_message);
	
	// Show the spinner while waiting for the response from openai
	create_spinner_div();
	
	
	// Scroll the page up by cicking on a div at the bottom of the page.
	simulateClick('scroll-page-up');
  
  
  
  //console.log(formdata);
  // Send an AJAX request to the server to process the form data
  var xhr = new XMLHttpRequest();
  xhr.open('POST', form.action, true);
  
  xhr.onload = function() {
	  
    if (xhr.status === 200) {
      var response = JSON.parse(xhr.responseText);
	  
	  var response_text = response.chat_text;
	  
	  message_history = response.message_history;
	  
	  console.log(response.message_history);
	  
	  
	  
	  // Write the response on the console
      //console.log(response.chat_text);
	  
	  
	  // Replace the suffixes with "":
		// This removes sentences like: How can I help you today?
		// For each suffix in the list...
		 suffixes_list.forEach(suffix => {
	      
			// Replace the suffix with nothing.
	        response_text = response_text.replace(suffix, "");
			
	  	});
		
	  
	  // *** Remove any html and then speak *** //
		////////////////////////////////////////////
		const cleaned_text = removeHtmlTags(response_text);
		//speak(cleaned_text);
	  
	  
	  // Format the response into separate paragrahs
	  var paragraph_response = formatResponse(response_text);
	 
	  
	  console.log(paragraph_response);
	  
	  var input_message = {
		  sender: bot_name,
	  	text: paragraph_response
		};
	
	
	//console.log(input_message.text);
	
	
	// Delete the div containing the spinner
	delete_spinner_div();

	// Add the message from Maiya to the chat
	addMessageToChat(input_message);
	
	
	// Scroll the page up by cicking on a div at the bottom of the page.
	simulateClick('scroll-page-up');
	
	// Put the cursor in the form input field
	const inputField = document.getElementById("user-input");
	inputField.focus();
	
	speak(cleaned_text);
	  
	  
    }
  };
  
  xhr.send(formData);
};

</script>


<script>

function quiet_please() {
	
	speechSynthesis.cancel();
	
}


	
function speak(text) { 
	

	// Create a new instance of SpeechSynthesisUtterance
	const utterance = new SpeechSynthesisUtterance();
	
	// Set the text that you want to speak
	utterance.text = text;
	
	  
	  // If speech recognition has been initialized.
	  // If the user just types then speech recognition 
	  // is not initialized and the recognition object does not exist.
	  if (window.recognition) {
		  
		  console.log('Stopping recognition...')
	  
		  // Pause (delete) the event listener.
		  // The handleEnd function identifies which event listener we want.
		  window.recognition.removeEventListener('end', handleEnd);
		  
		  // The recognition object has been attached to the window
		  // in order to make it available globally.
		  window.recognition.stop();
	  
  		}

	
		// Speak the text
		speechSynthesis.speak(utterance);
		
		
		// Only when the speech synthesis ends, start the mic.
		// If we don't use this then the event listener 
		// will start listening while the bot is still talking.
		// The bot will then hear it's own voice and respond to it.
		
		utterance.onend = function() {
		
			if (window.recognition) {
				
				console.log('Restarting recognition...')
			  
				// Add the event listener again.
				// The handleEnd function identifies which event listener we want.
				window.recognition.addEventListener('end', handleEnd);
				
				window.recognition.start();
	  		}
		};
	
}
	
</script>





<script>
	
// Event listener function
// When the end event is detected, the vent listener
// uses this function to restart the mic.
// In this way the mic always stays on.
// Adding and deleting the event listener is important to
// ensure that the mic stays on, but that it's also off
// when the bot is talking.
function handleEnd () {
	
  console.log('Event listener restarting mic...');
  window.recognition.start();
	  
  }
	

function initialize_recognition() {
	
	window.SpeechRecognition =
	window.SpeechRecognition || window.webkitSpeechRecognition;
	
	const recognition = new SpeechRecognition();
	
	recognition.continuous = true;
	
	// *** Comment out this line for better performance on Android. ***
	// When this line is commented out there's no intermediate voice detections,
	// however, the bot works much better on Android.
	recognition.interimResults = true;
	
	// Make the recognition object available globally
	window.recognition = recognition;
	
	
	console.log('recognition initialized')
	
	
	// Add event listener
	window.recognition.addEventListener('end', handleEnd);
	
	// Pause (Remove) the event listener
	//window.recognition.removeEventListener('end', handleEnd);
	
	
	window.recognition.start();

}




function submit_text_to_php(my_text) {
		// Select the input element by its id
		const inputElement = document.getElementById('user-input');
		
		// Set the value attribute
		inputElement.setAttribute('value', my_text);
		
		// Simulate a click on the form submit button
		// This will send the form to the php code for processing.
		simulateClick('submit-btn');
		
		// Clear the value that was set
		inputElement.setAttribute('value', "");
	}

	
	
	

// Source: Speech Recognition App Using Vanilla JavaScript
// https://www.youtube.com/watch?v=-k-PgvbktX4

function start_recog(submit_text_to_php) {
	
	initialize_recognition();

	const texts = document.querySelector(".texts");
	
	//window.SpeechRecognition =
	  //window.SpeechRecognition || window.webkitSpeechRecognition;
	
	//const recognition = new SpeechRecognition();
	//recognition.interimResults = true;
	
	
	//window.recognition = recognition;
	
	// Create a temporary p element where the voice detection 
	// will be displayed.
	let p = document.createElement("p");
	// Set the id attribute
	p.setAttribute('id', 'temp_p');
	
	
	recognition.addEventListener("result", (e) => {
		
		
	  texts.appendChild(p);
	  
	  let text = Array.from(e.results)
	    .map((result) => result[0])
	    .map((result) => result.transcript)
	    .join("");
	
	  p.innerText = text;
	  
	  if (e.results[0].isFinal) {
	
	    	// Delete the temporary p element that 
			// showed the voice detection.
			delete_temp_p();
	  
		  // Format the input into paragraphs. This
		  // adds paragrah html to the user's chat.
		  // It's main use is where the bot's long response needs 
		  // to be formatted into separate paragraphs.
		  text = formatResponse(text);
			
		// Use the form to submit the text to php for processing
		submit_text_to_php(text);
		
	  }
	  
	});


	//makeApiRequest(text);
	//window.recognition.start();
}


</script>



<?php
// This is important.
// If this is not done then the session variables will still
// be available even after the tab is closed. By doing this the
// session variables get deleted when the tab is closed.
// You can print out the message history to confirm that the
// session variable has been deleted: print_r($_SESSION['message_history']);

// remove all session variables
session_unset();

// destroy the session
session_destroy();
?>


