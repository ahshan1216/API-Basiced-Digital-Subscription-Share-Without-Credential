<?php
include '../identity.php';
session_start();
if (!isset($_SESSION['phone']) || empty($_SESSION['phone'])) {
  header('Location: ../auth/'); // Redirect to the admin dashboard or another page
  exit(); // Ensure no further code is executed
}
// Fetch categories and product counts from the database
$phone = $_SESSION['phone'];
$token = $_SESSION['token'];
$url = 'https://grohonn.com/api/api.php';
$data = [
  'action' => 'fetch_data',  // Action to fetch data
  'type' => 'seller',
  'phone' => $phone
];
// cURL initialization
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

$response = curl_exec($ch);


// Decode the response
$responseData = json_decode($response, true);
// Check if data fetching is successful
if ($responseData && isset($responseData['status']) && $responseData['status'] == 'success') {
  // Access fetched data
  $userData = $responseData['user_data'];
  $firstRow = $userData[0];

} else {
  // Handle errors
  echo 'error Fatching';
}
$totalSpend = 0;
$totalsell = 0;
$revinue1 = 0;

function logout()
{
  // Remove session data
  session_unset();
  session_destroy();

  // Redirect to login page
  header('Location: ../auth/');  // Use header function for redirection
  exit(); // Ensure no further code is executed
}

// Check token expiration in the response
if (isset($responseData) && $responseData['status'] === 'error' && $responseData['message'] === 'Token expired.') {
  logout(); // Call logout function if token expired
}
curl_close($ch);
?>

<!DOCTYPE html>
<html lang="en" class="" style="height: auto;">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $website_title; ?></title>
  <link rel="icon" href="../logo.webp" />

  <link rel="stylesheet" href="css/fontawesome-free/css/all.min.css">

  <link rel="stylesheet" href="css/dist/css/custom.css">




  <!-- Theme style -->
  <link rel="stylesheet" href="css/dist/css/adminlte.css">

  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="css/dist/css/OverlayScrollbars.min.css">


  <!-- jQuery -->
  <script src="css/jquery/jquery.min.js"></script>
  <!-- jQuery UI 1.11.4 -->
  <script src="css/jquery-ui/jquery-ui.min.js"></script>


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
          <a href="../admin/" class="nav-link"><?php echo $Dashboard_header; ?></a>
        </li>
      </ul>
      <!-- Right navbar links -->

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
    <div class="content-wrapper pt-3" style="min-height: 567.854px;">

      <!-- Main content -->
      <section class="content  text-dark">
        <div class="container-fluid">
          <style>
            .info-tooltip,
            .info-tooltip:focus,
            .info-tooltip:hover {
              background: unset;
              border: unset;
              padding: unset;
            }
          </style>
          <h1><?php echo $dashboard_welcome_note; ?></h1>
          <hr>
          <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box">
                <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-money-bill-alt"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Total Device You Have Paid</span>
                  <span class="info-box-number text-right">
                    <?php echo number_format($firstRow['seller_total_device']); // Format the number with commas ?></span>
                </div>

                <!-- /.info-box-content -->
              </div>

              <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-calendar-day"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Total Actual Device Used</span>
                  <span class="info-box-number text-right">
                    <?php echo number_format($firstRow['seller_actual_device']); // Format the number with commas ?></span>
                </div>

              </div>

            </div>
            <!-- /.col -->

            <!-- fix for small devices only -->
            <div class="clearfix hidden-md-up"></div>

            <!-- <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-calendar-day"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Total Customar</span>
                  <span class="info-box-number text-right"> 0</span>
                </div>

              </div>

            </div> -->
          </div>
          <div class="row">
            <div class="col-lg-12">
              <h4>Total Product</h4>
              comming soon
              <hr>
            </div>
          </div>



          <!-- <div class="row row-cols-4 row-cols-sm-1 row-cols-md-4 row-cols-lg-4">
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="col p-2 cat-items">
            <div class="callout callout-info">
                <span class="float-right ml-1">

                </span>
                <h5 class="mr-4"><b><?php echo $row['c_name']; ?></b></h5>

            </div>
        </div>
    <?php endwhile; ?>
</div> -->

          <div class="col-md-12">
            <h3 class="text-center" id="noData" style="display:none">No Data to display.</h3>
          </div>
          <script>
            function check_cats() {
              if ($('.cat-items:visible').length > 0) {
                $('#noData').hide('slow')
              } else {
                $('#noData').show('slow')
              }
            }
            $(function () {
              $('[data-toggle="tooltip"]').tooltip({
                html: true
              })
              check_cats()
              $('#search').on('input', function () {
                var _f = $(this).val().toLowerCase()
                $('.cat-items').each(function () {
                  var _c = $(this).text().toLowerCase()
                  if (_c.includes(_f) == true)
                    $(this).toggle(true);
                  else
                    $(this).toggle(false);
                })
                check_cats()
              })
            })
          </script>
        </div>
      </section>
      <!-- /.content -->
      <div class="modal fade" id="confirm_modal" role='dialog'>
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Confirmation</h5>
            </div>
            <div class="modal-body">
              <div id="delete_content"></div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" id='confirm' onclick="">Continue</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
      <div class="modal fade" id="uni_modal" role='dialog'>
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title"></h5>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" id='submit'
                onclick="$('#uni_modal form').submit()">Save</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
          </div>
        </div>
      </div>
      <div class="modal fade" id="uni_modal_right" role='dialog'>
        <div class="modal-dialog modal-full-height  modal-md" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title"></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span class="fa fa-arrow-right"></span>
              </button>
            </div>
            <div class="modal-body">
            </div>
          </div>
        </div>
      </div>
      <div class="modal fade" id="viewer_modal" role='dialog'>
        <div class="modal-dialog modal-md" role="document">
          <div class="modal-content">
            <button type="button" class="btn-close" data-dismiss="modal"><span class="fa fa-times"></span></button>
            <img src="" alt="">
          </div>
        </div>
      </div>
    </div>
    <!-- /.content-wrapper -->

    <!-- <footer class="main-footer text-sm">
        <strong>Copyright © 2024. 
         <a href=""></a> -->
    <!-- </strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
          <b> </b> v1.0
        </div>
      </footer>  -->
  </div>
  <!-- ./wrapper -->

  <script>
    $.widget.bridge('uibutton', $.ui.button)
  </script>

  <script src="css/dist/js/bootstrap.bundle.min.js"></script>

  <script src="css/dist/js/adminlte.js"></script>

</body>

</html>