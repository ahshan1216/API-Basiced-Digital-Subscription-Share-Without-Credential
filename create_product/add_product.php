<?php
session_start();

function sendData($url, $data)
{
    $ch = curl_init($url);
    $token = $_SESSION['token'] ?? null;

    if (!$token) {
        echo "Authorization token is missing.";
        return;
    }

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
       echo $responseData['message'] ?? "Unexpected response format.";
    }

    curl_close($ch);
}

if (isset($_POST['product_name']) && isset($_POST['cookies'])) {
    $product_name = trim($_POST['product_name']);
    $cookies = json_encode($_POST['cookies']); // Assuming cookies is an array or object
    $product_url = trim($_POST['product_url']);





    $url = "https://grohonn.com/api/api.php";
    $phone = $_SESSION['phone'] ?? null;

    if (!$phone) {
        echo "Phone number is missing in session.";
        exit;
    }


    $data = [
        "action" => "insert_data",
        "insertions" => [
            [
                "table" => "api_seller_cookies",
                "data" => [
                    "phone" => $phone,
                    "cookie_name" => $product_name,
                    "cookies" => $cookies,
                    "cookie_url" => $product_url
                ]
            ]

        ]
    ];


    // Send the data
    sendData($url, $data);

}


?>