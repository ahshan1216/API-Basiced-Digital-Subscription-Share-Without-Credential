<?php
include '../database/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the data from the AJAX request
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $user_active = mysqli_real_escape_string($conn, $_POST['user_active']);
    $seller_active = mysqli_real_escape_string($conn, $_POST['seller_active']);

    // Update the active status in both tables
    $updateQuery = "UPDATE api_users SET active='$user_active' WHERE phone='$phone';
                    UPDATE api_seller SET active='$seller_active' WHERE phone='$phone';";

    if (mysqli_multi_query($conn, $updateQuery)) {
        echo $phone;
    } else {
        echo "Error updating user status: " . mysqli_error($conn);
    }
}
?>
