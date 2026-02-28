<?php
session_start();
require_once __DIR__ . '/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$message = $_POST['message'] ?? '';
	$receiver_id = $_POST['receiver_id'] ?? null;

	$sender_id = $_SESSION['user_id'] ?? null;

	if(!$message || !$receiver_id || !$sender_id){
		http_response_code(405); //405 is for bad request
		echo "Missing data";
		exit;
	}	

	try {
		sendMessage($sender_id, $receiver_id, $message);
		echo "Message sent";
	} catch (Exception $e) {
		http_response_code(500); //500 is for server error
		echo "Erro sending message";
	}
} else {
	//If not a POST, reject it
	http_response_code(400); //400 is for bad method request
	echo "Invalid request method";
}
