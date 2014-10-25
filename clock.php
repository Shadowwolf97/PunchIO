<?php
include('includes/load.php');

if(isset($_GET["method"])) {
    if($_GET["method"] == "in") {
        $db = getMySQL();
        $time = time();
        $res = $db->query("INSERT INTO actions (time, action, user, project) VALUES ($time, 1, {$_SESSION["user"]->id}, 99)");
        header('location: /clock.php');
    }else if($_GET["method"] == "out") {
        $db = getMySQL();
        $time = time();
        $res = $db->query("INSERT INTO actions (time, action, user, project) VALUES ($time, 0, {$_SESSION["user"]->id}, 99)");
        header('location: /clock.php');
    }
}

function lastAction() {
    if(isset($data["clocked"])) {
        return $data["clocked"];   
    }else {
        $db = getMySQL();
        $res = $db->query("SELECT * FROM actions WHERE user={$_SESSION["user"]->id} ORDER BY time DESC LIMIT 1"); //Pull last action from DB
        $obj = $res->fetch_object();
        return ["id"=>$obj->actionid, "time"=>$obj->time, "project"=>$obj->project, "action"=>$obj->action];
    }
}

function timeToString($seconds) {
    $ret = [];
    if($seconds >= 86400) {$ret["days"] = floor($seconds/86400); $seconds = $seconds%86400;}
    if($seconds >= 3600) {$ret["hours"] = floor($seconds/3600); $seconds = $seconds%3600;}
    if($seconds >= 60) {$ret["minutes"] = floor($seconds/60); $seconds = $seconds%60;}
    $ret["seconds"] = $seconds;
    $out = "";
    foreach($ret as $k=>$v) {
        if($v > 1) {
            $out .= " ".$v." ".$k."s";
        }else {
            $out .= " ".$v." ".$k;
        }
    }
    $ret["string"] = trim($out);
    return $ret;
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
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><center>Time Clock</center></div>
                <div class="panel-body" style="text-align: center;">
                    <?php $data = lastAction(); $clocked = $data["action"] == 1; ?>
                    <h1>You Are Currently Clocked: <?php if($clocked){echo '<span style="color: green;">IN</span>';}else{echo '<span style="color: red;">OUT</span>';} ?></h1>
                    
                    <?php
                        if($clocked) {
                    ?>
                        <h3>Clocked Into Project: <?php echo $data["project"]; ?></h3>
                        <h3>Clocked in For: <?php echo timeToString(); ?></h3>
                    <?php
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>

    </div> <!-- /container -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
  </body>
</html>
