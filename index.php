<?php
require('includes/load.php');
if(isLoggedIn()) {
    header('location: /clock.php');
}
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
    <div style="width: 100VW; position: absolute; z-index: -1; top: 0; height: 100px; background: #8C8C8C;"></div>
    <div class="container-fluid" style="background: #E6E6E6;">
        
      <div class="jumbotron">
        <center><h1>PunchIO - Punch In, Punch Out.</h1></center>
        <h3><center>A simple way to track time spent on your projects.</center></h3><br/>
        <p style="text-align: center;">
            <a class="btn btn-lg btn-default" href="/login.php" role="button" style="width: 150px;">Login</a>
            <a class="btn btn-lg btn-primary" href="/register.php" role="button" style="width: 150px;">Register</a>
        </p>
      </div>

    </div> <!-- /container -->
    <div class="container" style="margin-top: 40px;">
        <div class="row">
            <div class="col-md-4">
                <div class="panel panel-success">
                    <div class="panel-heading">GitHub Integration</div>
                    <div class="panel-body">PunchIO has a smooth integration process for GitHub hoster repositories. Once you sign in with your GitHub account, you can auotmagically link your PunchIO projects and GitHub repos, to track commits that were made while you were Punched In.</div>
                </div>                
            </div>
            <div class="col-md-4">
                <div class="panel panel-primary">
                    <div class="panel-heading">Smooth Interface</div>
                    <div class="panel-body">PunchIO offers a streamlined interface so you don't have to hassle with it. Why spend your time configuring a website when you could be making the next Facebook?</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-info">
                    <div class="panel-heading">Track Your Progress</div>
                    <div class="panel-body">
                        PunchIO is a great tool to track your progress on any development project, especially if the project is hosted on github. As you begin to track how long certain tasks take to finish, you become more accurate when giving time estimates for just about anything.
                    </div>
                </div>
            </div>
        </div>  
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
  </body>
</html>
