<?php
session_start();

// Ensure the user is authenticated and token is available
if (!isset($_SESSION['token']) || !isset($_POST['id'])) {
    echo "Unauthorized request.";
    exit();  // Ensure no further code is executed
}

$id = $_POST['id'];  // ID of the record to delete
$token = $_SESSION['token'];  // JWT token stored in the session

// API URL to delete the record
$url = 'https://grohonn.com/api/api.php';

// Data to send in the request
$data = [
    'action' => 'delete_data',  // Action for the API to handle
    'table' => 'api_users',  // Specify the table from which the data should be deleted
    'id' => $id  // The ID of the record to delete
];

// Initialize cURL
$ch = curl_init($url);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));  // Send data as JSON
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $token  // Pass JWT token for authorization
]);

// Execute the request and get the response
$response = curl_exec($ch);
curl_close($ch);

// Decode the response
$responseData = json_decode($response, true);

// Check if the deletion was successful
if (isset($responseData['status']) && $responseData['status'] == 'success') {
    echo "Record deleted successfully!";
} else {
    echo "Error deleting record: " . ($responseData['message'] ?? 'Unknown error');
}
?>
