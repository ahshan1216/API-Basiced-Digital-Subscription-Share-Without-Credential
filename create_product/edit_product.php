<?php
session_start();
$id = $_POST['id'];
$product_name = trim($_POST['product_name']);  // Sanitize the product name
$cookies = trim($_POST['cookies']);  // Sanitize the cookies data
// Ensure the user is authenticated and required POST data is available
if (!isset($_SESSION['token']) || !isset($_POST['id']) || !isset($_POST['product_name']) || !isset($_POST['cookies'])) {
    echo "Unauthorized request or missing required fields.";
    exit();
}

$id = $_POST['id'];  // Get the product ID
$product_name = trim($_POST['product_name']);  // Sanitize the product name
$cookies = trim($_POST['cookies']);  // Sanitize the cookies data
$token = $_SESSION['token'];  // Get the JWT token from the session
$product_url = trim($_POST['product_url']);

// API URL to send the update request to
$api_url = 'https://grohonn.com/api/api.php';

// Data to be sent to the API
$data = [
    'action' => 'update_data',  // API action
    'table' => 'api_seller_cookies',  // Table name
    'id' => $id,  // Record ID
    'updateFields' => [  // Fields to be updated should be in this array
        'cookie_name' => $product_name  ,// Updated product name (cookie_name)
        'cookie_url' =>  $product_url
    ],
    'cookies' => $cookies  // Updated cookies data to be saved in the file
];

// Initialize cURL
$ch = curl_init($api_url);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));  // Send data as JSON
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $token  // Include the JWT token for authentication
]);

// Execute the cURL request
$response = curl_exec($ch);

// Check if any error occurred during cURL execution
if (curl_errno($ch)) {
    echo 'cURL error: ' . curl_error($ch);
    exit();
}

// Close cURL session
curl_close($ch);

// Decode the response from the API
$responseData = json_decode($response, true);

// Check the API response
if (isset($responseData['status']) && $responseData['status'] == 'success') {
    echo "Product updated successfully!";
} else {
    echo "Error updating category: " . ($responseData['message'] ?? 'Unknown error');
}
?>
