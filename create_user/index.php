<?php
session_start();
include '../identity.php';


if (!isset($_SESSION['phone']) || empty($_SESSION['phone'])) {
    header('Location: ../auth/'); // Redirect to the admin dashboard or another page
    exit(); // Ensure no further code is executed
}

?>


<!DOCTYPE html>
<html lang="en" class="" style="height: auto;">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $website_title; ?></title>
    <link rel="icon" href="../logo.webp" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="css/fontawesome-free/css/all.min.css">



    <!-- Theme style -->
    <link rel="stylesheet" href="css/dist/css/adminlte.css">
    <link rel="stylesheet" href="css/dist/css/custom.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="css/dist/css/OverlayScrollbars.min.css">
    <!-- Include jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Include Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <!-- Include Select2 JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>







</head>

<body class="sidebar-mini layout-fixed control-sidebar-slide-open layout-navbar-fixed sidebar-mini-md sidebar-mini-xs"
    data-new-gr-c-s-check-loaded="14.991.0" data-gr-ext-installed="" style="height: auto;">
    <div class="wrapper">
        <style>
            .user-img {
                position: absolute;
                height: 27px;
                width: 27px;
                object-fit: cover;
                left: -7%;
                top: -12%;
            }

            .btn-rounded {
                border-radius: 50px;
            }

            /* Ensure Select2 styles are applied inside modal */
            .select2-container .select2-selection--single {
                height: auto !important;
                padding: 6px !important;
                border: 1px solid #ccc !important;
            }

            .select2-container .select2-search__field {
                width: 100% !important;
                display: block !important;
            }

            /* Force Select2 dropdown above modal */
            .select2-container--open {
                z-index: 1051 !important;
                /* Higher than Bootstrap modal's z-index */
            }

            .select2-container--default .select2-selection--single .select2-selection__rendered {
                padding-left: 8px !important;
            }
        </style>
        <!-- Navbar -->
        <nav
            class="main-header navbar navbar-expand navbar-dark bg-navy shadow border border-light border-top-0  border-left-0 border-right-0 text-sm">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="../admin" class="nav-link"><?php echo $Dashboard_header; ?></a>
                </li>
            </ul>
            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">

                <!-- Messages Dropdown Menu -->
                <li class="nav-item">
                    <div class="btn-group nav-link">
                        <button type="button" class="btn btn-rounded badge badge-light dropdown-toggle dropdown-icon"
                            data-toggle="dropdown">
                            <span><img src="../logo.webp" class="img-circle elevation-2 user-img"
                                    alt="User Image"></span>
                            <span class="ml-3">Adminstrator Admin</span>
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu" role="menu">

                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="../logout/"><span class="fas fa-sign-out-alt"></span>
                                Logout</a>
                        </div>
                    </div>
                </li>
                <li class="nav-item">

                </li>

            </ul>
        </nav>
        <!-- /.navbar -->
        <style>
            .main-sidebar a {
                color: white !important;
            }
        </style>
        <!-- Main Sidebar Container -->
        <?php include '../slidebar/slidebar.php'; ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper pt-3">
            <section class="content text-dark">
                <div class="container-fluid">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Create Users</h3>
                            <div class="card-tools">
                                <a href="javascript:void(0)" id="manage_budget" class="btn btn-flat btn-sm btn-primary"
                                    data-toggle="modal" data-target="#addNewModal">
                                    <span class="fas fa-plus"></span> Add New
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-stripped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Customar Phone</th>
                                        <th>Customar Limit Device</th>
                                        <th>Customar Used Device</th>
                                        <th>Product</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="user_list">
                                    <!-- Categories will be dynamically loaded here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <!-- Bootstrap Modal for Adding New Category -->
        <div class="modal fade" id="addNewModal" tabindex="-1" role="dialog" aria-labelledby="addNewModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addNewModalLabel">Add New User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="add_new_form">
                            <div class="form-group">
                                <label for="new_email" class="col-form-label">Email:</label>
                                <input type="text" class="form-control" id="new_email" name="new_email" required>
                                <span id="add_error_msg" class="text-danger"></span>
                            </div>
                            <div class="form-group">
                                <label for="new_phone" class="col-form-label">Phone Number:</label>
                                <input type="text" class="form-control" id="new_phone" name="new_phone" required>
                                <span id="add_error_msg" class="text-danger"></span>
                            </div>
                            <div class="form-group">
                                <label for="new_password" class="col-form-label">Password:</label>
                                <input type="text" class="form-control" id="new_password" name="new_password" required>
                                <span id="add_error_msg" class="text-danger"></span>
                            </div>





                        </form>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="save_new_user">Create Users</button>
                    </div>
                </div>
            </div>
        </div>

<style>
    .modal-lg-custom {
    max-width: 90%; /* Adjust as needed */
    width: auto; /* Allows dynamic width */
}

</style>
        <!-- Bootstrap Modal for Editing -->
        <!-- Triple-Sided Bootstrap Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg-custom" role="document">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit User, Extension Addition, and Extension Details
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="width: 100%; height: auto;">
                        <div class="row">
                            <!-- First Column - Edit User -->
                            <div class="col-md-4">
                                <h5>Edit User</h5>
                                <form id="edit_form">
                                    <input type="hidden" id="edit_id" name="id">
                                    <input type="hidden" id="old_phone" name="old_phone">
                                    <div class="form-group">
                                        <label for="edit_email" class="col-form-label">Email:</label>
                                        <input type="text" class="form-control" id="edit_email" name="email" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_phone" class="col-form-label">Phone Number:</label>
                                        <input type="text" class="form-control" id="edit_phone" name="phone" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_password" class="col-form-label">Password:</label>
                                        <input type="text" class="form-control" id="edit_password" name="password"
                                            required>
                                    </div>
                                    
                                    <button type="button" class="btn btn-primary" id="save_changes">Save</button>
                                </form>
                            </div>

                            <!-- Second Column - Extension Addition -->
                            <div class="col-md-4">
                                <h5>Extension Addition</h5>
                                <form id="extension_form">
                                    <div class="form-group">
                                        <label for="extension_name" class="col-form-label">Extension Name:</label>
                                        <select class="form-control" id="extension_name" name="extension_name" required>
                                            <!-- Options will be dynamically populated -->
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="add_device_limit" class="col-form-label">Customer Device
                                            Limit:</label>
                                        <input type="number" class="form-control" id="add_device_limit"
                                            name="add_device_limit" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="add_month" class="col-form-label">Month:</label>
                                        <input type="number" class="form-control" id="add_month" name="add_month"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="add_price" class="col-form-label">Price:</label>
                                        <input type="number" class="form-control" id="add_price"
                                            name="add_price"></input>
                                    </div>
                                    <button type="button" class="btn btn-primary" id="add_extension">Add</button>
                                </form>
                            </div>

                            <!-- Third Column - Extension Details -->
                            <div class="col-md-4">
                                <h5>Extension Details</h5>
                                <div id="details" class="details-container">
                                    <!-- Details will be populated here via AJAX -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>



    </div>
    <!-- ./wrapper -->


    <!-- Bootstrap 4 -->
    <script src="css/dist/js/bootstrap.bundle.min.js"></script>

    <script>

        // Fetch and display users from the database
        function loadUsers() {
            $.ajax({
                url: 'fetch_users.php',  // Update this PHP script to fetch users
                method: 'GET',
                success: function (data) {
                    $('#user_list').html(data);  // Update the container where users will be listed
                }
            });
        }
        // function loadExten() {
        //     $.ajax({
        //         url: 'fetch_users.php',  // Update this PHP script to fetch users
        //         method: 'GET',
        //         success: function (data) {
        //             $('#user_list').html(data);  // Update the container where users will be listed
        //         }
        //     });
        // }

        // Delete user
        function deleteUser(id) {
            if (confirm('Are you sure you want to delete this user?')) {
                $.ajax({
                    url: 'delete_user.php',  // Update this PHP script for deleting users
                    method: 'POST',
                    data: { id: id },
                    success: function (response) {
                        alert(response);
                        loadUsers();  // Refresh user list after deletion
                    }
                });
            }
        }

        function initializeSelect2(cookieNames) {

            const $select = $('#extension_name');
            $select.empty(); // Clear existing options
            $select.append(new Option("Select a cookie name", "")); // Default option

            cookieNames.forEach(function (cookieName) {
                $select.append(new Option(cookieName, cookieName));
            });

            // Destroy any previous Select2 instance to prevent conflicts
            if ($select.data('select2')) {
                $select.select2('destroy');
            }

            // Re-initialize Select2
            $select.select2({
                placeholder: "Select a cookie name",
                allowClear: true,
                dropdownParent: $('#editModal'), // Ensure dropdown is rendered inside the modal
                minimumResultsForSearch: 0 // Always show search box even for few options
            });

            // Focus on the search field when the modal is shown
            $select.on('select2:open', function () {
                document.querySelector('.select2-search__field').focus();
            });
            // Capture the change event and log the selected value
            $select.on('change', function () {
                var selectedValue = $select.val();

                $('#extension_name').val(selectedValue);
                var hi = $('#extension_name').val();
                console.log('hi', hi);
            });
        }

        function fetchDetailsByPhone(phoneNumber) {
        //console.log('Fetching details for phone:', phoneNumber);
        
        // Automatically fetch details based on the phone number
        $.ajax({
            url: 'details.php', // Your PHP file
            method: 'POST',
            data: { CusPhn: phoneNumber }, // Send phone number as POST data
            success: function(response) {
                // Populate the details container with the response
                $('#details').html(response);
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error: " + status + error);
                $('#details').html('<p>Error loading details.</p>');
            }
        });
    }
        // Show edit modal with user data
        function editUser(id,email, phone, password, user_login_acitve, cookieNames) {
            $('#edit_email').val(email);
            $('#edit_phone').val(phone);
            $('#edit_password').val(password);
            $('#edit_id').val(id);

            // Display cookie names if needed (example: in a console log or a hidden field for internal tracking)
            console.log("Cookie Names:", cookieNames); // For debugging or tracking

            // Optional: Show cookie names in an input or display field if required
            var CusPhn = $('#edit_phone').val();;
            fetchDetailsByPhone(CusPhn); // Call the function with the phone number
            initializeSelect2(cookieNames);
            $('#editModal').modal('show');  // Show the modal for editing users

        }



        


        // Save edited user
        $('#save_changes').on('click', function () {
            var id = $('#edit_id').val();
            var email = $('#edit_email').val();
            var phone = $('#edit_phone').val();
            var password = $('#edit_password').val();


            // Prepare data to be sent via AJAX
            var data = {
                id: id,
                email: email,
                phone: phone,
                
                password: password,
               
            };

            $.ajax({
                url: 'edit_users.php',  // Update this PHP script for editing users
                method: 'POST',
                data: data,
                success: function (response) {
                    alert(response);
                    console.log('Response:', response); // Log the response for debugging
                    $('#editModal').modal('hide');  // Close the modal
                    var CusPhn = $('#edit_phone').val();;
                    fetchDetailsByPhone(CusPhn); // Call the function with the phone number
                },
                error: function (xhr, status, error) {
                    console.error('AJAX error:', status, error);
                    alert('An error occurred. Please try again.');
                }
            });
        });

        // Load users when the page is ready
        $(document).ready(function () {
            loadUsers();
        });
    </script>


    <script src="css/dist/js/adminlte.js"></script>
    <script>
        // Add new user
        $('#save_new_user').on('click', function () {
            // Collect all the form data
            var new_email = $('#new_email').val();
            var new_phone = $('#new_phone').val();
            var new_password = $('#new_password').val();

            var price = $('#price').val(); // Added price field
            var customar_total_device = $('#customar_total_device').val();

            var month = $('#month').val();


            // Clear any previous error message
            $('#add_error_msg').text('');

            // Validate that all required fields are filled
            if (new_email === '' || new_phone === '' || new_password === '') {
                $('#add_error_msg').text('All fields are required');
                return;
            }

            // Prepare data to be sent via AJAX
            var data = {
                new_email: new_email,
                new_phone: new_phone,
                new_password: new_password,


            };

            // If the role is seller, include seller-specific fields


            $.ajax({
                url: 'add_users.php',  // Modify the PHP script accordingly
                method: 'POST',
                data: data,
                success: function (response) {
                    if (response === 'User with this email or phone already exists') {
                        $('#add_error_msg').text(response);
                    } else {
                        alert(response);
                        $('#addNewModal').modal('hide');
                        loadUsers();  // Refresh user list
                    }
                },
                error: function (xhr, status, error) {
                    // Log error to console (useful for debugging)
                    console.error('AJAX error:', status, error);
                    $('#add_error_msg').text('An error occurred. Please try again.');
                }
            });
        });



        $('#add_extension').click(function (e) {
            e.preventDefault(); // Prevent the form from submitting in the traditional way

            // Gather form data
            var formData = {
                extension_name: $('#extension_name').val(),
                add_device_limit: $('#add_device_limit').val(),
                add_month: $('#add_month').val(),
                add_price: $('#add_price').val(),
                cus_phone: $('#edit_phone').val()
            };
            

            // Ensure that all required fields are filled
            if (!formData.extension_name || !formData.add_device_limit || !formData.add_month || !formData.add_price) {
                alert("Please fill in all required fields.");
                return;
            }

            // AJAX request to send the form data to the server
            $.ajax({
                url: 'add_extension.php', // Change this URL to your actual server-side endpoint
                type: 'POST',
                data: formData, // Send the form data as key-value pairs
                dataType: 'json', // Expected response type
                success: function (response) {
                    // Handle a successful response
                    console.log('Server Response:', response);
                    $('#extension_name').val(null).trigger('change'); // Reset value
                    // Optionally, reset the form after a successful response
                    $('#extension_form')[0].reset();
                    alert(response.message);
                    var CusPhn = $('#edit_phone').val();;
                    fetchDetailsByPhone(CusPhn); // Call the function with the phone number
                    // You can also reload or update the page if necessary
                    // location.reload(); // Uncomment this line to reload the page after the form submission
                },
                error: function (xhr, status, error) {
                    // Handle any errors that occur during the request
                    console.error('AJAX Error:', status, error);
                    alert('An error occurred while adding the extension. Please try again.');
                }
            });
        });


    </script>

</body>

</html>