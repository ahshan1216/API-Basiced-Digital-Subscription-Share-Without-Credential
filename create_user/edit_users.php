<?php
session_start();

$token = $_SESSION['token'];
$phone = $_SESSION['phone'];
$url = "https://grohonn.com/api/api.php";

function edituserData($url, $token, $data)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token
    ]);
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo json_encode(["status" => "error", "message" => 'cURL error: ' . curl_error($ch)]);
        return null;
    }

    curl_close($ch);
    return json_decode($response, true);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize input
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);

    $password = htmlspecialchars($_POST['password']);
    $id = trim($_POST['id']);
// Define the data array to send to the API
$CususerData = edituserData($url, $token, [
    'action' => 'update_data',
    'table' => 'api_users',
    'id' => $id,
    'updateFields' => [
        'email' => $email,
        'customer_phone' => $phone,
        'password' => $password
    ]
]);

// Output the response from the API
if ($CususerData) {
    echo json_encode($CususerData["message"]); // Send the API's response back to the client
} else {
    echo json_encode(["status" => "error", "message" => "Failed to update data."]);
}
}
    
?>