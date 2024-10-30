<?php
// Define the file path
$filePath = __DIR__ . '/website.txt';


// Check if the file exists
if (file_exists($filePath)) {
    // Read the contents of the file
    $fileContents = file_get_contents($filePath);

    // Use regex to extract the value of website_name
    if (preg_match('/sidebar_name\s*=\s*"([^"]+)"/', $fileContents, $matches)) {
        // $matches[1] will contain the value
        $websiteName = $matches[1];
        // echo $websiteName;
    } else {
        echo "website_name not found in the file.";
    }
    if (preg_match('/Login_name\s*=\s*"([^"]+)"/', $fileContents, $matches)) {
        // $matches[1] will contain the value
        $login_name = $matches[1];
        // echo $login_name;
    } 
    if (preg_match('/website_title\s*=\s*"([^"]+)"/', $fileContents, $matches)) {
        // $matches[1] will contain the value
        $website_title = $matches[1];
        // echo $login_name;
    } 
    if (preg_match('/Dashboard_header\s*=\s*"([^"]+)"/', $fileContents, $matches)) {
        // $matches[1] will contain the value
        $Dashboard_header = $matches[1];
        // echo $login_name;
    } 
    if (preg_match('/Dashboard_header\s*=\s*"([^"]+)"/', $fileContents, $matches)) {
        // $matches[1] will contain the value
        $dashboard_welcome_note = $matches[1];
        // echo $login_name;
    } 
} else {
    echo "File does not exist.";
}
?>
