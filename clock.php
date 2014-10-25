<?php
include('includes/load.php');

if(isset($_GET["method"])) {
    if($_GET["method"] == "in") {
        $db = getMySQL();
        $time = time();
        if(isset($_POST["proj"])) {
            $ii = intval($_POST["proj"]);
        }else {
            $ii = intval($_GET["proj"]);   
        }
        $res = $db->query("INSERT INTO actions (time, action, user, project) VALUES ($time, 1, {$_SESSION["user"]->id}, $ii)");
        header('location: /clock.php');
    }else if($_GET["method"] == "out") {
        $db = getMySQL();
        $time = time();
        $ii = intval($_GET['proj']);
        $res = $db->query("INSERT INTO actions (time, action, user, project) VALUES ($time, 0, {$_SESSION["user"]->id}, $ii)");
        header('location: /clock.php');
    }else if($_GET["method"] == "project") {
        if($_POST) {
            $db = getMySQL();
            $name = $db->escape_string($_POST['name']);
            $res = $db->query("INSERT INTO projects (projectname, projectowner) VALUES ('$name', {$_SESSION["user"]->id})");
            if(!empty($_POST["check"])) {
                header('location: /clock.php?method=in&proj='.$db->insert_id);   
                exit;
            }
        }
        header('location: /clock.php');
    }
}

function lastAction() {
    if(isset($data["clocked"])) {
        return $data["clocked"];   
    }else {
        $db = getMySQL();
        $res = $db->query("SELECT * FROM actions WHERE user={$_SESSION["user"]->id} ORDER BY time DESC LIMIT 1"); //Pull last action from DB
        if($obj = $res->fetch_object())
            return ["id"=>$obj->actionid, "time"=>$obj->time, "project"=>$obj->project, "action"=>$obj->action];
        return ["action"=>0];
    }
}

function timeToString($seconds) {
    $ret = [];
    if($seconds >= 86400) {$ret["days"] = floor($seconds/86400); $seconds = $seconds%86400;}
    if($seconds >= 3600) {$ret["hours"] = floor($seconds/3600); $seconds = $seconds%3600;}
    if($seconds >= 60) {$ret["minutes"] = floor($seconds/60); $seconds = $seconds%60;}
    if($seconds > 0) {$ret["seconds"] = $seconds;}
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
                    <h1>You Are Currently Punched: <?php if($clocked){echo '<span style="color: green;">IN</span>';}else{echo '<span style="color: red;">OUT</span>';} ?></h1><br />
                    
                    <?php
                        if($clocked) {
                    ?>
                        <h3>Clocked Into Project: <?php $db = getMySQL(); $res = $db->query("SELECT * FROM projects WHERE projectid={$data["project"]}"); $dat = $res->fetch_object(); echo $dat->projectname; ?></h3>
                        <h3>Clocked in For: <span class="time"></span></h3>
                        <center><a href="clock.php?method=out&proj=<?php echo $data["project"]; ?>" class="btn btn-danger btn-lg" style="width: 95%;">Punch Out</a></center>
                    <?php
                        }else {
                    ?>   
                        
                        <center>
                            <div style="max-width: 360px; min-width: 200px;">
                                <form method="post" action="clock.php?method=in" id="1"> 
                                <div>
                                    <div class="input-group" style="margin-bottom: 5px;">
                                        <span class="input-group-addon">Project</span>
                                        <select class="form-control" name="proj">
                                            <option value="-1">Create a New Project</option>
                                            <?php
                            
                                                $db = getMySQL();
                                                $res = $db->query("SELECT * FROM projects WHERE projectowner={$_SESSION["user"]->id} ORDER BY projectname ASC");
                                                while($obj = $res->fetch_object()) {
                                                    echo "<option value='{$obj->projectid}'>{$obj->projectname}</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <input type="submit" class="btn btn-success form-control" value="Punch In">
                                </div>
                            </form>
                            </div>
                        </center>
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
    <?php if($clocked) {
       ?>
      <script>
            var startTime = <?php echo $data["time"]*1000; ?>;
            var offset = -1 * ((new Date()) - <?php echo time()*1000; ?>);
                          
            function display() {
                var endTime = new Date();
                var timeDiff = (endTime - startTime) + offset;
                timeDiff /= 1000;
                var seconds = Math.round(timeDiff % 60);
                timeDiff = Math.floor(timeDiff / 60);
                var minutes = Math.round(timeDiff % 60);
                timeDiff = Math.floor(timeDiff / 60);
                var hours = Math.round(timeDiff % 24);
                $(".time").html(hours + " hours, " + minutes + " minutes, and " + seconds + " seconds");
                setTimeout(display, 1000);
            }

            display();
        </script>
       <?php
    }else {
    ?>
      <div id="wrap" style="background: rgba(0,0,0,.3); height: 100vh; width: 100vw; postion: absolute; left: 0px; top: 0px; display: none; z-index: 100000;">
          <div class="panel panel-success" style="display: none; width: 350px; height: auto;" id="popup">
            <div class="panel-heading">Create a new project<a class="pull-right" style="cursor: pointer;" id="close">X</a></div>
            <div class="panel-body">
                <form action="clock.php?method=project" method="post">
                    <input type="text" name="name" id="nme" placeholder="Project Name" class="form-control" style="margin-bottom: 5px"/>
                    <center><input type="checkbox" name="check" value="check" checked> Punch In Automatically<br /></center>
                    <input type="submit" value="Create Project" class="btn btn-success form-control" style="margin-top: 3px">
                </form>
            </div>
          </div>
      </div>
      
      <script>
      
          jQuery.fn.center = function (boo) {
                this.css("position","absolute");
                if(boo) {
                    this.css("top", Math.max(0, (($(window).height() - $(this).outerHeight()) * .25) + 
                                                            $(window).scrollTop()) + "px");
                }else {
                    this.css("top", Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) + 
                                                            $(window).scrollTop()) + "px");   
                }
                this.css("left", Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) + 
                                                            $(window).scrollLeft()) + "px");
                return this;
            }
          
          $(document).keyup(function(e) {
              if (e.keyCode == 27) { $('#close').click(); }   // esc
          });
          
          $('#popup').center(true);
          $('#wrap').center(false);
          
          $('#close').click(function() {
            $("#popup").fadeOut(); 
            $("#wrap").fadeOut();
          });
          
          $('#1').submit(function(e) {
             if($('select').val() == -1) {
                 e.preventDefault();   
                 $("#popup").fadeIn();
                 $("#wrap").fadeIn();
                 $("#nme").focus();
             }
          });
      
      </script>
      
    <?php
    }
    ?>
  </body>
</html>
