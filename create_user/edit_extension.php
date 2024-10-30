<?php
session_start();
// edit_extension.php
header('Content-Type: application/json');

// Get the JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Check if session token and phone are set
if (!isset($_SESSION['token']) || !isset($_SESSION['phone'])) {
    echo json_encode(["status" => "error", "message" => "User not authenticated."]);
    exit;
}

$token = $_SESSION['token'];
$phone = $_SESSION['phone'];
$url = "https://grohonn.com/api/api.php";

// Function to send update request to the API
function editData($url, $token, $data)
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

// Check if the necessary data is provided
if (!isset($data['id']) || !isset($data['editMonth']) || !isset($data['editActive'])) {
    echo json_encode(["status" => "error", "message" => "Missing data."]);
    exit;
}

$id = trim($data['id']);
$editMonth = trim($data['editMonth']);
$editActive = trim($data['editActive']);
$editActive = trim($data['editActive']);
$customer_actual_limit = trim($data['customer_actual_limit']);
$customer_device_limit = trim($data['customer_device_limit']);
$price = trim($data['price']);

// Define the data array to send to the API
$CusData = editData($url, $token, [
    'action' => 'update_data',
    'table' => 'api_extension',
    'id' => $id,
    'updateFields' => [
        'month' => $editMonth,
        'active' => $editActive,
        'customer_device_limit' => $customer_device_limit,
        'customer_actual_limit'=> $customer_actual_limit,
        'price' => $price
    ]
]);

// Output the response from the API
if ($CusData) {
    echo json_encode($CusData); // Send the API's response back to the client
} else {
    echo json_encode(["status" => "error", "message" => "Failed to update data."]);
}
?>
