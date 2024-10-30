<?php
session_start();
function sendData($url, $data) {
    $ch = curl_init($url);
    $token = $_SESSION['token'];
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'cURL Error: ' . curl_error($ch);
    } else {
        $responseData = json_decode($response, true);
       // echo "Full Response: " . json_encode($responseData);
        if (isset($responseData['message'])) {
           echo $responseData['message'];
        }
    }

    curl_close($ch);
}

$url = "https://grohonn.com/api/api.php"; // Replace with your actual endpoint

if (isset($_POST['new_email'])) {
    $new_email = trim($_POST['new_email']);
    $new_phone = trim($_POST['new_phone']);
    $new_password = trim($_POST['new_password']);
  



    $phone = $_SESSION['phone']; // Ensure this is defined

    // Validate phone numbers
    if (empty($new_phone) || !preg_match('/^[0-9]+$/', $new_phone) || empty($phone)) {
        echo 'Invalid phone number.';
        exit;
    }

    $data = [
        "action" => "insert_data",
        "insertions" => [
            
            [
                "table" => "api_users",
                "data" => [
                    "phone" => $phone,
                    "customer_phone" => $new_phone,
                    "email" => $new_email,
                    "password" => $new_password,
                   
                    "month"=> 'check api_extension'
                ]
            ]
        ]
    ];

    sendData($url, $data);
}
?>
