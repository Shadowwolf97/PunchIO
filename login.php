<?php
include('includes/load.php');

if($_POST) {
    if(!empty($_POST['email']) && !empty($_POST['password'])) {
        $db = getMySQL();
        $email = $db->escape_string($_POST["email"]);
        $password = $db->escape_string($_POST["password"]);
        $res = $db->query("SELECT * FROM users WHERE email='$email'");
        if($res->num_rows === 1) {
            $obj = $res->fetch_object();
            $hash = $obj->password;
            if(password_verify($password, $hash)) {
                //Login Success! :)
                $_SESSION['user'] = User::FromID($obj->userid);
                header('location: /');
            }else {
                $error = "The email/password combination is not valid";   
            }
        }else {
            $error = "That email is not registered.";
        }
    }else {
        header('location: /login.php');   
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
        <div class="col-md-4 col-md-offset-4" id="login">
            <div class="panel panel-default">
                <div class="panel-heading">Login</div>
                <div class="panel-body">
                    <form action="login.php" method="post">
                        <input type="text" autocomplete="off" placeholder="Email Address" name="email" style="margin-bottom: 5px;" class="form-control">
                        <input type="password" placeholder="Password" name="password" style="margin-bottom: 5px;" class="form-control">
                        <input type="submit" class="btn btn-success form-control" value="Login">
                    </form>
                </div>
            </div>
        </div>
    </div>

    </div> <!-- /container -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
  </body>
</html>
