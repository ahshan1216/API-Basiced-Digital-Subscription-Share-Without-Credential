<?php
session_start();

// Get phone and token from session
$phone = $_SESSION['phone'] ?? null;
$token = $_SESSION['token'] ?? null;
$url = "https://grohonn.com/api/api.php";

// Check if the user is logged in and the token exists
if (!$phone || !$token) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in or token missing.']);
    exit();
}

// Get the raw POST data
$data = json_decode(file_get_contents('php://input'), true);

// Validate the input data
if (!isset($data['id']) || !is_numeric($data['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid or missing ID in input data.']);
    exit();
}

// Extract and sanitize the ID
$id = trim($data['id']);

// Prepare data for deletion request
$requestData = [
    'action' => 'delete_data',  // Define action for the API
    'table' => 'api_extension',  // Specify target table
    'id' => $id  // The record ID to delete
];

/**
 * Sends a DELETE request to the API to delete a record.
 *
 * @param string $url - The API endpoint URL.
 * @param string $token - JWT token for authorization.
 * @param array $data - Data to send with the request.
 * @return void
 */
function deleteData($url, $token, $data)
{
    // Initialize cURL
    $ch = curl_init($url);

    // Set cURL options for the POST request
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));  // Send data as JSON
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token  // Include JWT token for authorization
    ]);

    // Execute the request and capture the response
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Capture HTTP response code for additional error handling
    curl_close($ch);

    // Decode the response
    $responseData = json_decode($response, true);

    // Check the HTTP response and whether the deletion was successful
    if ($httpCode == 200 && isset($responseData['status']) && $responseData['status'] == 'success') {
        echo json_encode(['status' => 'success', 'message' => 'Record deleted successfully!']);
    } else {
        $errorMessage = $responseData['message'] ?? 'Unknown error occurred';
        echo json_encode(['status' => 'error', 'message' => "Error deleting record: " . $errorMessage]);
    }
}

// Call the deleteData function with required parameters
deleteData($url, $token, $requestData);
?>
