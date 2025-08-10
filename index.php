<!DOCTYPE html>
<html>
<head>
  <title>Users</title>
  <link rel="stylesheet" type="text/css" href="css/Users.css">
  <style>
    body {
      font-family: Arial, sans-serif;
    }
    .tbl-header, .tbl-content {
      overflow-x: auto;
    }
    .tbl-header {
      background-color: #f5f5f5;
      margin-bottom: 10px;
    }
    .tbl-header table, .tbl-content table {
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      padding: 10px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }
    th {
      background-color: #f8f8f8;
    }
    @media screen and (max-width: 768px) {
      th, td {
        padding: 8px;
        font-size: 14px;
      }
      th, td {
        white-space: nowrap;
      }
      .tbl-header, .tbl-content {
        margin: 0 -10px;
      }
    }
    @media screen and (max-width: 480px) {
      th, td {
        padding: 5px;
        font-size: 12px;
      }
      th, td {
        display: block;
        text-align: right;
        border-bottom: none;
      }
      td {
        border-bottom: 1px solid #ddd;
      }
      th::before {
        content: attr(data-label);
        float: left;
        font-weight: bold;
      }
    }
  </style>
  <script>
    $(window).on("load resize", function() {
      var scrollWidth = $('.tbl-content').width() - $('.tbl-content table').width();
      $('.tbl-header').css({'padding-right':scrollWidth});
    }).resize();
  </script>
</head>
<body>
  <?php include 'header.php'; ?> 
  <main>
    <section>
      <!-- User table -->
      <h1 class="slideInDown animated">Here are all the Users</h1>
      <div class="tbl-header slideInRight animated">
        <table cellpadding="0" cellspacing="0" border="0">
          <thead>
            <tr>
              <th data-label="ID | Name">ID | Name</th>
              <th data-label="Serial Number">Serial Number</th>
              <th data-label="Gender">Gender</th>
              <th data-label="Finger ID">Finger ID</th>
              <th data-label="Date">Date</th>
              <th data-label="Time In">Time In</th>
            </tr>
          </thead>
        </table>
      </div>
      <div class="tbl-content slideInRight animated">
        <table cellpadding="0" cellspacing="0" border="0">
          <tbody>
            <?php
              //Connect to database
              require 'connectDB.php';

              $sql = "SELECT * FROM users WHERE NOT username='' ORDER BY id DESC";
              $result = mysqli_stmt_init($conn);
              if (!mysqli_stmt_prepare($result, $sql)) {
                  echo '<p class="error">SQL Error</p>';
              } else {
                mysqli_stmt_execute($result);
                $resultl = mysqli_stmt_get_result($result);
                if (mysqli_num_rows($resultl) > 0) {
                    while ($row = mysqli_fetch_assoc($resultl)) {
            ?>
                      <tr>
                        <td data-label="ID | Name"><?php echo $row['id']; echo " | "; echo $row['username']; ?></td>
                        <td data-label="Serial Number"><?php echo $row['serialnumber']; ?></td>
                        <td data-label="Gender"><?php echo $row['gender']; ?></td>
                        <td data-label="Finger ID"><?php echo $row['fingerprint_id']; ?></td>
                        <td data-label="Date"><?php echo $row['user_date']; ?></td>
                        <td data-label="Time In"><?php echo $row['time_in']; ?></td>
                      </tr>
            <?php
                    }
                }
              }
            ?>
          </tbody>
        </table>
      </div>
    </section>
  </main>
</body>
</html>
