<?php
session_start();

$phone = $_SESSION['phone'];
$token = $_SESSION['token'];
// Define the API endpoint URL
$url = "https://grohonn.com/api/api.php";

// Check if the user is logged in and the token exists
if (!$phone || !$token) {
    echo 'User not logged in or token missing.';
    exit();
}

// Function to fetch data from API based on type
function fetchData($url, $token, $data) {
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
        echo 'cURL error: ' . curl_error($ch);
        return null;
    }

    curl_close($ch);
    return json_decode($response, true);
}

// Fetch user data
$userData = fetchData($url, $token, [
    'action' => 'fetch_data',
    'type' => 'users',
    'phone' => $phone
]);

// Fetch cookies data
$cookiesData = fetchData($url, $token, [
    'action' => 'fetch_data',
    'type' => 'cookies',
    'phone' => $phone
]);




$cookieNames = [];
if ($cookiesData && isset($cookiesData['status']) && $cookiesData['status'] === 'success') {
    // Assuming each entry in 'user_data' is an associative array with a 'cookie_name' field
    foreach ($cookiesData['user_data'] as $cookie) {
        $cookieNames[] = $cookie['cookie_name'];  // Replace 'cookie_name' with the actual key if different
    }
}

// Convert the cookie names array to JSON format for JavaScript
$cookieNamesJson = json_encode($cookieNames);

if ($userData['status'] != 'success') {
    // Code to execute when the status is not 'success'
     $check=$userData['message'] ?? 'unknownerror';   
}
// Process user data response
if ($userData && isset($userData['status']) && $userData['status'] === 'success') {
    $i = 1;
    foreach ($userData['user_data'] as $row) {
        $email = htmlspecialchars($row['email'] ?? '');
        $id = htmlspecialchars($row['id'] ?? '');
        $cus_phone = htmlspecialchars($row['customer_phone'] ?? '');
        $price = htmlspecialchars($row['price'] ?? '');
        $customer_device_limit = htmlspecialchars($row['customer_device_limit'] ?? '0');
        $customer_Used_limit = htmlspecialchars($row['customer_actual_limit'] ?? '0');
        $password = htmlspecialchars($row['password'] ?? '');
        $user_login_acitve= $row['active'];
        
        
        echo "<tr>
                <td>{$i}</td>
                <td>{$cus_phone}</td>
                 <td>{$customer_device_limit}</td>
                 <td>{$customer_Used_limit}</td>
                <td align='center'>
                    <button class='btn btn-flat btn-primary btn-sm' onclick='editUser(\"{$id}\",\"{$email}\", \"{$cus_phone}\", \"{$password}\",\"{$user_login_acitve}\", {$cookieNamesJson})'>
                        <span class='fa fa-edit'></span> Edit
                    </button>
                </td>
                <td align='center'>

                    <button class='btn btn-flat btn-danger btn-sm' onclick='deleteUser({$row['id']})'>
                        <span class='fa fa-trash'></span> Delete
                    </button>
                </td>
            </tr>";
        $i++;
    }
} else {
    echo "<tr><td colspan='6'>Error fetching user data: " . ($userData['message'] ?? 'Unknown error') . "</td></tr>";
}
function logout() {
    // Remove session data
    session_unset();
    session_destroy();
    
    // Redirect to login page
    header('Location: ../auth/');  // Use header function for redirection
    exit(); // Ensure no further code is executed
}

// Check token expiration in the response
if ($check === 'Unknown error') {
    logout(); // Call logout function if token expired
}

?>
