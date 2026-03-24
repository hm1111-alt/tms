<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Green Themed Datepicker</title>
  
  <!-- Bootstrap 5 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
  
  <!-- Bootstrap Datepicker CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
  
  <style>
    /* Green color design for the datepicker input */
    .datepicker-wrapper {
      position: relative;
      max-width: 300px;
    }

    /* Input styling with green theme */
    .datepicker-wrapper input {
      padding-right: 40px;
      border-radius: 30px;
      border: 2px solid #28a745; /* Green border */
      background-color: #f0fff4; /* Light green background */
      color: #28a745; /* Green text color */
    }

    /* Calendar icon inside the input field */
    .datepicker-wrapper .calendar-icon {
      position: absolute;
      top: 50%;
      right: 15px;
      transform: translateY(-50%);
      color: #28a745;
      font-size: 20px;
      cursor: pointer;
    }

    /* Change input border and glow effect on focus */
    .datepicker-wrapper input:focus {
      border-color: #218838;
      box-shadow: 0 0 8px rgba(33, 136, 56, 0.5); /* Green glowing effect */
    }

    /* Button styled in green */
    .btn-green {
      background-color: #28a745;
      border-color: #28a745;
      color: white;
      border-radius: 30px;
    }

    /* Button hover effect */
    .btn-green:hover {
      background-color: #218838;
      border-color: #1e7e34;
    }

    /* Datepicker calendar customization */
    .datepicker-dropdown .datepicker-days table td.active {
      background-color: #28a745 !important; /* Active date highlight */
      color: white !important;
    }
    
    .datepicker-dropdown .datepicker-days table td.today {
      background-color: #d4edda !important; /* Today's date background */
      border-color: #28a745;
    }

    /* Calendar's header background (month/year) */
    .datepicker-dropdown .datepicker-switch {
      background-color: #28a745;
      color: white !important;
    }
    .datepicker-dropdown .datepicker-switch:hover {
      color: #149531 !important;
    }

    /* Day names and days outside the current month */
    .datepicker-dropdown .datepicker-days table th,
    .datepicker-dropdown .datepicker-days table td.old,
    .datepicker-dropdown .datepicker-days table td.new {
      color: #6c757d; /* Grey text for inactive dates */
    }
  </style>
</head>
<body>

<div class="container my-5">
  <h2>Green Themed Datepicker</h2>
  
  <!-- Datepicker input with a calendar icon and green theme -->
  <div class="datepicker-wrapper">
    <input type="text" class="form-control" id="datepicker" placeholder="Select a date">
    <span class="calendar-icon">
      <i class="bi bi-calendar-date"></i>
    </span>
  </div>

  <button type="button" class="btn btn-green mt-3">Submit</button>
</div>

<!-- Bootstrap 5 JS and Popper -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap Datepicker JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">

<script>
  // Initialize the datepicker with green theme settings
  $('#datepicker').datepicker({
                    dateFormat: 'yy-mm-dd',
    autoclose: true,
    todayHighlight: true
  });

  // Trigger datepicker on calendar icon click
  $('.calendar-icon').on('click', function() {
    $('#datepicker').focus();
  });
</script>

</body>
</html>
