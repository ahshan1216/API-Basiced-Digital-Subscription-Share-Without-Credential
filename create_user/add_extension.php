<?php
session_start();

// Check if required session variables are set
if (!isset($_SESSION['phone']) || !isset($_SESSION['token'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Session data missing. Please log in again.'
    ]);
    exit;
}

$phone = $_SESSION['phone'];
$token = $_SESSION['token'];

// Set response header to JSON
header('Content-Type: application/json');

// API endpoint
$url = "https://grohonn.com/api/api.php"; // Replace with your actual endpoint

/**
 * Sends data to the API using cURL.
 */
function sendData($url, $data, $token) {
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        return ['status' => 'error', 'message' => 'cURL Error: ' . $error];
    } else {
        curl_close($ch);
        return json_decode($response, true) ?: ['status' => 'error', 'message' => 'Invalid response from API.'];
    }
}

/**
 * Fetches the total device limit from the API.
 */

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Capture and sanitize input data
    $extension_name = isset($_POST['extension_name']) ? htmlspecialchars(trim($_POST['extension_name'])) : null;
    $add_device_limit = isset($_POST['add_device_limit']) ? intval($_POST['add_device_limit']) : null;
    $add_month = isset($_POST['add_month']) ? intval($_POST['add_month']) : null;
    $add_price = isset($_POST['add_price']) ? floatval($_POST['add_price']) : null;
    $cus_phone = isset($_POST['cus_phone']) ? htmlspecialchars(trim($_POST['cus_phone'])) : null;

    // Validate required fields
    if (!$extension_name || !$add_device_limit || !$add_month || !$add_price || !$cus_phone) {
        echo json_encode([
            'status' => 'error',
            'message' => 'All fields are required.'
        ]);
        exit;
    }
    date_default_timezone_set('Asia/Dhaka');

    // Prepare data for insertion into `api_extension`
    $insertData = [
        "action" => "insert_data",
        "insertions" => [
            [
                "table" => "api_extension",
                "data" => [
                    "phone" => $phone,
                    "customer_phone" => $cus_phone,
                    "customer_extension_name" => $extension_name,
                    "customer_device_limit" => $add_device_limit,
                    "price" => $add_price,
                    "month" => $add_month,
                    "customar_buy_date" => date('Y-m-d H:i:s'),
                    "active" => 1
                ]
            ]
        ]
    ];
  

    // Insert data into `api_extension`
    $insertResponse = sendData($url, $insertData, $token);
    if ($insertResponse['status'] == 'success') {
        echo json_encode($insertResponse);
        exit;
    }
    else
    {
        echo json_encode($insertResponse);
        exit;
    }

    

} else {
    // Handle non-POST requests
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method.'
    ]);
}
?>
