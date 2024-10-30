<?php
session_start();

$phone = $_SESSION['phone'];
$token = $_SESSION['token'];
$url = "https://grohonn.com/api/api.php";

// Check if the user is logged in and the token exists
if (!$phone || !$token) {
    echo 'User not logged in or token missing.';
    exit();
}
// Assuming database connection is already established in this file
$CusPhn = null;

// Check the request method to retrieve CusPhn
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $CusPhn = isset($_POST['CusPhn']) ? $_POST['CusPhn'] : null;
}

// Function to fetch data from API based on type
function fetchData($url, $token, $data)
{
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

// Fetch user data from the API for `extension` type
$CusData = fetchData($url, $token, [
    'action' => 'fetch_data',
    'type' => 'extension',
    'phone' => $phone,
    'cusphn' => $CusPhn
]);

// Check if the API response was successful
if (!$CusData || $CusData['status'] !== 'success') {
    echo 'No data Found.';
    exit();
}

$data = $CusData['user_data'];
?>
<!-- Add this modal at the end of your file -->
<div id="editModal1"
    style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); align-items: center; justify-content: center;">
    <div style="background-color: white; padding: 20px; border-radius: 5px; width: 300px;">
        <h3>Edit Extension</h3>
        <label>Price</label>
        <input type="text" id="price" style="width: 100%; margin-bottom: 10px;">

        <label>Customer Device Limit:</label>
        <input type="text" id="customer_device_limit" style="width: 100%; margin-bottom: 10px;">

        <label>Customer Used Device </label>
        <input type="text" id="customer_used_device" style="width: 100%; margin-bottom: 10px;">

        <label>Month</label>
        <input type="text" id="editMonth" style="width: 100%; margin-bottom: 10px;">
        <label>Active</label>

        <select id="editActive" style="width: 100%; margin-bottom: 10px;">
            <option value="1">Yes</option>
            <option value="0">No</option>
        </select>

        <button onclick="submitEdit()">Save Changes</button>
        <button onclick="closeModal()">Cancel</button>
    </div>
</div>

<table style="width: 100%; border-collapse: collapse;" border="1">
    <thead>
        <tr>
            <th style="border: none; padding: 8px;">Extension Name</th>

            <th style="border: none; padding: 8px;">Order Date</th>
            <th style="border: none; padding: 8px;">Expiry Date</th>
            <th style="border: none; padding: 8px;">Month</th>
            <th style="border: none; padding: 8px;">Active</th>
            <th style="border: none; padding: 8px;">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $item): ?>
            <tr>

                <td style="border: none; padding: 8px;"><?php echo htmlspecialchars($item['customer_extension_name']); ?>
                </td>
                <td style="border: none; padding: 8px;"><?php echo htmlspecialchars($item['customar_buy_date'] ?? ''); ?>
                </td>
                <td style="border: none; padding: 8px;"><?php echo htmlspecialchars($item['validity'] ?? ''); ?></td>
                <td style="border: none; padding: 8px;"><?php echo htmlspecialchars($item['month'] ?? ''); ?></td>
                <td style="border: none; padding: 8px;">
                    <?php echo htmlspecialchars($item['active'] == 1 ? 'Yes' : 'No'); ?>
                </td>

                <td style="border: none; padding: 8px;">
                    <button class="btn btn-primary"
                        onclick="openEditModal('<?php echo htmlspecialchars($item['id']); ?>','<?php echo htmlspecialchars($item['month'] ?? ''); ?>','<?php echo htmlspecialchars($item['active'] ?? ''); ?>','<?php echo htmlspecialchars($item['customer_device_limit'] ?? ''); ?>','<?php echo htmlspecialchars($item['customer_actual_limit'] ?? ''); ?>','<?php echo htmlspecialchars($item['price'] ?? ''); ?>')">
                        Edit
                    </button>

                    <button class="btn btn-danger"
                        onclick="deleteExtension('<?php echo htmlspecialchars($item['id']); ?>')">Delete</button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
    // Open the modal with prefilled data
    function openEditModal(id, month, active,customer_device_limit,customer_actual_limit,price) {
        
        document.getElementById('editActive').value = active;
        document.getElementById('editMonth').value = month;
        document.getElementById('editModal1').style.display = 'flex';

        document.getElementById('customer_device_limit').value = customer_device_limit;
        document.getElementById('customer_used_device').value = customer_actual_limit;
        document.getElementById('price').value = price;
        // Store id for the edit action
        document.getElementById('editModal1').dataset.id = id;
        
    }

    // Close the modal
    function closeModal() {
        document.getElementById('editModal1').style.display = 'none';
    }

    // Submit the edited data
    function submitEdit() {
       
        const id = document.getElementById('editModal1').dataset.id;
        const editActive = document.getElementById('editActive').value;
        const editMonth = document.getElementById('editMonth').value;
        const  customer_device_limit = document.getElementById('customer_device_limit').value  ;
        const   customer_actual_limit = document.getElementById('customer_used_device').value  ;
        const price = document.getElementById('price').value;
        // Prepare data for the request
        const requestData = {
           
            id: id,
            editMonth: editMonth,
            editActive: editActive,
            customer_device_limit:customer_device_limit,
            customer_actual_limit:customer_actual_limit,
            price:price
        };

        // Make the AJAX call to update data
        fetch('edit_extension.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(requestData)
        })
            .then(response => {
                if (!response.ok) throw new Error("Network response was not ok");
                return response.json();
            })
            .then(data => {
                if (data.status === "success") {
                    alert("Extension updated successfully.");
                    var CusPhn = $('#edit_phone').val();;
                    fetchDetailsByPhone(CusPhn); // Call the function with the phone number
                } else {
                    alert("Error: " + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert("An error occurred while updating the extension. Please try again.");
            })
            .finally(() => closeModal());  // Close modal after submission
    }

    // JavaScript function for Delete
    function deleteExtension(id) {
        if (confirm("Are you sure you want to delete this extension?")) {
            fetch('delete_extension.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: id })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        alert("Extension deleted successfully.");
                        var CusPhn = $('#edit_phone').val();;
                        fetchDetailsByPhone(CusPhn); // Call the function with the phone number
                    } else {
                        alert("Error: " + data.message);
                    }
                });
        }
    }
</script>