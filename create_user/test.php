<?php
session_start();
$phone = $_SESSION['phone'];

function fetchUserID($phone) {
    $token = $_SESSION['token'];
    // API endpoint URL
    $apiUrl = 'https://grohonn.com/api/api.php'; // Replace with your actual API URL

    // Create the data to send
    $data = [
        'action' => 'fetch_data',
        'type' => 'api_seller',
        'phone' => $phone
    ];

    // Initialize cURL
    $ch = curl_init($apiUrl);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token
    ]);

    // Execute the request
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        echo 'cURL Error: ' . curl_error($ch);
        return null;
    }

    // Close cURL session
    curl_close($ch);

    // Decode the JSON response
    $result = json_decode($response, true);

    // Check for success and return the ID if available
    if (isset($result['status']) && $result['status'] === 'success') {
        return $result['user_data'][0]['id'] ?? null; // Return the ID or null if not found
    } else {
        echo 'Error: ' . ($result['message'] ?? 'Unknown error');
        return null;
    }
}

// Example usage
$phoneNumber = $phone; // Replace with the phone number you want to check
$userID = fetchUserID($phoneNumber);

if ($userID) {
    echo "User ID for phone $phoneNumber is: $userID";
} else {
    echo "User ID not found for phone $phoneNumber.";
}
