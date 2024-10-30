<?php
session_start(); // Start the session

// Function to clear session data and cookies
function clearSessionAndCookies() {
    $_SESSION = array();

    // If it's desired to kill the session, also delete the session cookie.
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Finally, destroy the session.
    session_destroy();
}

// Check if a redirect parameter is set
$redirectUrl = '../auth/';
$redirect = isset($_GET['redirect']) && $_GET['redirect'] == 'true';

// Clear session or JWT-based authorization
$headers = getallheaders();
header('Content-Type: application/json');

if (isset($headers['Authorization'])) {
    // JWT-based logout
    $jwt = str_replace("Bearer ", "", $headers['Authorization']);

    // Send JSON response (JWT is stateless; no server action needed)
    if ($redirect) {
        header("Location: $redirectUrl");
        exit();
    } else {
        echo json_encode([
            "status" => "success",
            "message" => "Logged out successfully using JWT."
        ]);
        exit();
    }

} elseif (isset($_SESSION['phone'])) {
    // Session-based logout
    clearSessionAndCookies();

    if ($redirect) {
        header("Location: $redirectUrl");
        exit();
    } else {
        header("Location: $redirectUrl");
        echo json_encode([
            "status" => "success",
            "message" => "Logged out successfully using session."
        ]);
        exit();
    }
} else {
    header("Location: $redirectUrl");
    // No active session or token found
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "No active session or token found to log out."
    ]);
    exit();
}
?>
