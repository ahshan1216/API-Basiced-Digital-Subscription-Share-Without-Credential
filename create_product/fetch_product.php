<?php
session_start();

$phone=$_SESSION['phone'];
$token = $_SESSION['token'];
// Define the API endpoint URL
$url = "https://grohonn.com/api/api.php";

// The phone number to fetch data for
// Check if the user is logged in and the token exists
if (!$phone || !$token) {
    echo 'User not logged in or token missing.';
    exit();  // Stop execution if not logged in
}

// Data to be sent to the API
$data = [
    'action' => 'fetch_data', 
    'type'=> 'cookies', 
    'phone' => $phone
];

// Initialize cURL
$ch = curl_init($url);

// Set the cURL options
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $token  // Add the JWT token to the Authorization header
]);

// Execute the cURL request
$response = curl_exec($ch);

// Check if there was a cURL error
if (curl_errno($ch)) {
    echo 'cURL error: ' . curl_error($ch);
} else {
    // Decode the JSON response
    $responseData = json_decode($response, true);
    if ($responseData['status'] != 'success') {
        // Code to execute when the status is not 'success'
         $check=$responseData['message'] ?? 'unknownerror';   
    }
    

    // Check if the response has data
    if ($responseData && isset($responseData['status']) && $responseData['status'] == 'success') {
        $userData = $responseData['user_data'];  // Assuming the user data comes in this field

        // Check if there's user data and that it's an array
        if (!empty($userData) && is_array($userData)) {
            $i = 1;
            foreach ($userData as $row) {
                if (!empty($row['cookie_name'])) {  // Only render if cookie_name is not empty
                    echo "<tr>
                            <td>{$i}</td>
                            <td>{$row['cookie_name']}</td> <!-- Display the cookie_name from the API response -->
                            <td align='center'>
                                <button class='btn btn-flat btn-primary btn-sm' onclick='editCategory({$row['id']}, \"{$row['cookie_name']}\", " . json_encode($row['cookies']) . ", \"{$row['cookie_url']}\")'>
                                    <span class='fa fa-edit'></span> Edit
                                </button>
                                <button class='btn btn-flat btn-danger btn-sm' onclick='deleteUser({$row['id']})'>
                                    <span class='fa fa-trash'></span> Delete
                                </button>
                            </td>
                          </tr>";
                    $i++;
                }
                else {
                    // No data found
                    echo "<tr><td colspan='3'>No categories found.</td></tr>";
                }
            }
        } else {
            // No data found
            echo "<tr><td colspan='3'>No Product found.</td></tr>";
        }
    } else {
        // Handle any error message from the API response
        echo "<tr><td colspan='3'>Error: " . $check . "</td></tr>";
    }
    
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
if ($check === 'unknownerror') {
    logout(); // Call logout function if token expired
}
// Close cURL session
curl_close($ch);
?>
