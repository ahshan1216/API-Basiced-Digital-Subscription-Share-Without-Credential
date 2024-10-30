<?php
session_start();
include '../identity.php';
if (isset($_SESSION['phone']) ) {
    header('Location: ../admin/'); // Redirect to the admin dashboard or another page
    exit(); // Ensure no further code is executed
  }
// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitize user input to prevent XSS attacks
    $phone = htmlspecialchars(trim($_POST['username']));  // Assuming 'username' is the phone number
    $password = htmlspecialchars(trim($_POST['password']));

    // Check if the phone and password fields are not empty
    if (empty($phone) || empty($password)) {
        $error_message = 'Please enter both phone number and password.';
    } else {
        // API URL
        $url = 'https://grohonn.com/api/api.php';  // Ensure this is an HTTPS connection

        // Prepare the data to be sent in JSON format
        $data = [
            'action' => 'login',  // Specify the action for login
            'phone' => $phone,
            'password' => $password
        ];

        // Initialize cURL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        // Execute the request and get the response
        $response = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Get HTTP status code
        curl_close($ch);
// Log the response for debugging purposes
error_log("API Response: " . $response);
        // Decode the response
        $responseData = json_decode($response, true);

        // Check the HTTP status code and response for login status
        if ($http_status === 200 && isset($responseData['status']) && $responseData['status'] == 'success') {
            // Login successful, set session variables including the JWT token
            $_SESSION['user_id'] = $responseData['user_id'];
            $_SESSION['phone'] = $responseData['phone'];
            $_SESSION['role'] = $responseData['role'];
            $_SESSION['token'] = $responseData['token']; // Store the JWT token in the session

            // Redirect to dashboard or another page after login
            header('Location: ../admin/');
            exit();
        } elseif ($http_status === 403 && isset($responseData['message']) && $responseData['message'] == 'Account is inactive. Please contact support.') {
            // Handle inactive account scenario
            $error_message = 'Your account is inactive. Please contact support.';
        } else {
            // Handle invalid credentials or other errors
            $error_message = $responseData['message'];
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en" style="height: auto;">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $website_title; ?></title>
    <link rel="icon" href="../logo.webp" />
    <link rel="stylesheet" href="css/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="css/dist/css/adminlte.css">
</head>
<body class="hold-transition login-page bg-navy">
    
    <h2 class="text-center mb-4 pb-3"><?php echo $login_name; ?></h2>
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-body">
                <p class="login-box-msg text-dark">Sign in to start your session</p>

                <!-- Display error message if login fails -->
                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

                <form id="login-frm" action="" method="post">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="username" placeholder="Phone Number" autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="password" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
