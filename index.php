<?php
// URL of the website to redirect to
$redirect_url = "auth/";


// Perform the redirect
header("Location: " . $redirect_url);

// Ensure the script stops after the redirect
exit();
?>
