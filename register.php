<?php
include('includes/load.php');

if($_POST) {
    $db = getMySQL();
    $email = $db->escape_string($_POST['email']);
    $res = $db->query("SELECT userid FROM users WHERE email='$email'");
    if($res->num_rows) {
        //Someone already has an account with that email
        $error = "That email is already registered!";
    }else {
        //Go ahead and create the account
        $password = hashPassword($_POST['password']);
        $name = $db->escape_string($_POST['name']);
        $res = $db->query("INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')");
        if($res !== false) {
            header('location: /login.php');   
        }else {
            $error = "There was an error creating your account";
        }
    }
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
      
    <div class="container">
        
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-heading">Register for PunchIO!</div>
                    <div class="panel-body">
                        <form action="register.php" method="post">
                            <input type="text" placeholder="Email" class="form-control" name="email" style="margin-bottom: 5px;" id="email"/>
                            <input type="text" placeholder="Name" class="form-control" name="name" style="margin-bottom: 5px;" id="name"/>
                            <input type="password" placeholder="Password" class="form-control" name="password" style="margin-bottom: 5px;" id="pw1"/>
                            <input type="password" placeholder="Confirm Password" class="form-control" name="verify" style="margin-bottom: 5px;" id="pw2"/>
                            <input type="submit" class="btn btn-success pull-right" style="width: 60%;"/>
                            <input type="reset" class="btn btn-danger pull-left" style="width: 39%;"/>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
    </div> <!-- /container -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
    <script>
        $('form').submit(function(e) {
            if($('#pw1').val() == "" || $('#pw2').val() == "" || $('#name').val() == "" || $('#email').val() == "") {
                alert("All fields are required!");
                e.preventDefault();
            }else if($('#pw1').val() !== $('#pw2').val()) {
                alert("Your passwords do not match!");
                e.preventDefault();
            }
        });
    </script>
  </body>
</html>
