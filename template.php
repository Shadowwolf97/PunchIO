<?php
include('includes/load.php');
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
      
    <title>PunchIO</title>
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
  <body>

    <?php getNavbar(); ?>
      
    <div class="container">
        
      <div class="jumbotron">
        <h1>PunchIO - Punch In, Punch Out.</h1>
        <h3><center>A simple way to track time spent on your projects.</center></h3>
        <p>
          <center>
            <a class="btn btn-lg btn-default" href="/login.php" role="button" style="margin-right: 20px;">Login</a>
            <a class="btn btn-lg btn-primary" href="/register.php" role="button">Register</a>
          </center>
        </p>
      </div>

    </div> <!-- /container -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
  </body>
</html>
