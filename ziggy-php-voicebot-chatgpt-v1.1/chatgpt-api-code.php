<?php
session_start();

include "name_config.php";


// PHP Config
//------------
	
// Your API Key
$apiKey = 'your-api-key';


$model_type = "gpt-3.5-turbo";
$url = 'https://api.openai.com/v1/chat/completions';


// If this number plus the number of tokens in the message_history exceed
// the max value for the model (e.g. 4096) then the response from the api will
// an error dict instead of the normal message response. Thos error dict will
// contain an error message saying that the number of tokens for 
// this model has been exceeded.
$max_tokens = 300; //300

// 0 to 2. Higher values like 0.8 will make the output more random, 
// while lower values like 0.2 will make it more focused and deterministic.
// Alter this or top_p but not both.
$temperature = 0;

// -2 to 2. Higher values increase the model's likelihood to talk about new topics.
// Reasonable values for the penalty coefficients are around 0.1 to 1.
$presence_penalty = 0; 

// -2 to 2. Higher values decrease the model's likelihood to repeat the same line verbatim.
// Reasonable values for the penalty coefficients are around 0.1 to 1.
$frequency_penalty = 0;


//$system_setup_message = "Your name is " . $bot_name . ". You are a friendly assistant.";


$system_setup_message = <<<EOT
Your name is $bot_name. You are a kind and friendly roleplay chat companion.
The user's words are being converted from speech to text using Javascript SpeechRecognition.
The speech recognition text may contain errors.
You optimize for poor quality speech detection.
Your responses are being converted from text to speech using Javascript SpeechSynthesis.
You optimize your responses for SpeechSynthesis.
You add a full stop at the end of each bullet point.
You use a friendly and casual female tone.
You always greet the user and introduce yourself.
EOT;


// Replace newline characters with a space.
// This is important for ensuring that the csv file is created correctly.
// Every line in the csv file ends with a new line character. 
// If there are newline characters in the system message then this causes errors
// when creating and then later loading the csv file.
$system_setup_message = str_replace("\n", " ", $system_setup_message);




// If the list does NOT exist, create an empty array
if (!isset($_SESSION['message_history'])) {
	
	// Create a messages list
	$_SESSION['message_history'] = array();
	//$messages = $_SESSION['message_history'];
	
	// Append the system role to the messages list.
	// This will included in every message that get's submitted
	$_SESSION['message_history'][] = array("role" => "system", "content" => $system_setup_message);
	

} else {
	
	// Assign the session variable to $messages
	//$messages = $_SESSION['message_history'];
	
}




// This function cleans and secures the user input
function test_input(&$data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = strip_tags($data);
		$data = htmlentities($data);
		
		return $data;
	}



	


// This code is triggered when the user submits a message
//--------------------------------------------------------

if (isset($_REQUEST["my_message"]) && empty($_REQUEST["robotblock"])) {
	
	$my_message = $_REQUEST["my_message"];
	
	// Clean and secure the user's text input
	$my_message = test_input($my_message);

	$headers = array(
	    "Authorization: Bearer {$apiKey}",
	    "Content-Type: application/json"
	);
	
	
	// Append the user's message to the messages list.
	// Remember that system role is already in the messages list.
	$_SESSION['message_history'][] = array("role" => "user", "content" => $my_message);
	
	// Define data
	$data = array();
	$data["model"] = $model_type;
	$data["messages"] = $_SESSION['message_history'];
	$data["max_tokens"] = $max_tokens;
	$data["temperature"] = $temperature;
	$data["presence_penalty"] = $presence_penalty;
	$data["frequency_penalty"] = $frequency_penalty;
	
	
	
	// init curl
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	
	$result = curl_exec($curl);
	
	if (curl_errno($curl)) {
	    echo 'Error:' . curl_error($curl);
	} else {
		
	    $generatedText = json_decode($result, true);
		//echo $generatedText;
		//print_r($generatedText['choices'][0]['message']['content']);
		
		
		
		
		// Process the response from OpenAI
		// The API can return:
		// 1- A dict containing the reponse message or
		// 2- A different dict containing the error message.
		
		// 1- If the API returns a dict containing the response message.
		if (isset($generatedText['choices'][0]['message']['content'])) {
		
			$message = $generatedText['choices'][0]['message']['content'];
			
			
			// Append the response message to the session list
			// This will save the response 
			$_SESSION['message_history'][] = array("role" => "assistant", "content" => $message);
			
			// Display a message on the page
			// *** This is what we need to process on the index.php page ***
			//$response = array('success' => true, 'chat_text' => $message);
			
			$response = array('message_history' => $_SESSION['message_history'], 'chat_text' => $message);
		  	echo json_encode($response);
			
			
			
			
		// 2- If the API returns an error dict.
		// This can hapen when:
		// 1. The API is overloaded with requests. 
		// (The user should simply send the message again.)
		// 2. The context length has been exceeded.
		// Here we are checking if the doct has a key called 'error'.
		} else if (isset($generatedText['error'])) {
			
			// Get the error message and error code
			$message = "Error: " . $generatedText['error']['code'] . "<br>" . $generatedText['error']['message'];
			
			
			// Append the response message to the session list
			// This will save the response 
			$_SESSION['message_history'][] = array("role" => "assistant", "content" => $message);
			

			
			
			// Display a message on the page
			// *** This is what we need to process on the index.php page ***
			$response = array('success' => true, 'chat_text' => $message);
		  	echo json_encode($response);
			
		
		} else {
			
			// When the max tokens is exceeded that API returns a null response.
			// The system on the OpenAi side also freezes.
			
			// Get the finish reason
			$finish_reason = $generatedText['choices'][0]['finish_reason'];
			
			
			
			$message = "Status: " . $finish_reason . "<br>Admin: If the status is blank (null) it means that the token count has been exceeded. Please close this browser tab to clear the chat memory. Then start a new chat.";
			
			
			// Display a message on the page
			$response = array('success' => false, 'chat_text' => $message);
		  	echo json_encode($response);
		}
		
		
		
	}
	
	curl_close($curl);
	
}

?>