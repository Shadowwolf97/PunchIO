<?php
include('includes/load.php');

if(isset($_GET['remove'])) {
    $db = getMySQL();
    $id = intval($_GET['remove']);
    $db->query("DELETE FROM projects WHERE projectid=$id");
    $db->query("DELETE FROM actions WHERE project=$id");
    header('location: /projects.php');
}

function totalTime($projectid) {
    $db = getMySQL();
    $res = $db->query("SELECT * FROM actions WHERE project=$projectid ORDER BY actionid ASC");
    $data = Array();
    $tmp = Array();
    while($d = $res->fetch_object()) {
        $tmp[] = $d->time;
        if(count($tmp) >= 2) {
            $big = $tmp[0];
            if($tmp[1] > $big) {
                $big = $tmp[1];
                $low = $tmp[0];
            }else {
                $low = $tmp[1];   
            }
            $data[] = [$big, $low];
            $tmp = [];
        }
    }    
    //tmp[1] == in 
    //tmp[0] == out
    //out > in
    
    $total = 0;
    
    foreach($data as $k=>$v) {
        $total += ($v[0]-$v[1]);
    }
    return $total;
}?>

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
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
      
    <style>
        .ic {
            color: #000;
            font-size: 120%;
        }
        
        a.ic:hover {
            color: #000;
            text-decoration: none;
        }
        
        tr {
            height: 20px;
        }
        
        td {
            line-height: 20px;   
        }
    </style>
  <body>

    <?php getNavbar(); ?>
      
    <div class="container">
        
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">My Projects</div>
                <table class="table">
                    <tr>
                        <th>Project Title</th>
                        <th><center>Total Time</center></th>
                        <th></th>
                        <th><center>Options</center></th>
                    </tr>
                    <?php
                    $db = getMySQL();
                    $res = $db->query("SELECT * FROM projects WHERE projectowner={$_SESSION['user']->id}");
                    while($obj = $res->fetch_object()) {
                        echo "<tr><td>{$obj->projectname}</td><td style='text-align: center'>".(formatSeconds(totalTime($obj->projectid)))."</td><td style='text-align: right;'><a href='/sessions.php?proj={$obj->projectid}'>View Sessions</a></td><td><center><a class='ic' href='/github.php?proj={$obj->projectid}'><i class='fa fa-github'></i></a> <a href='/projects.php?remove={$obj->projectid}' class='ic'><i class='fa fa-times'></i></a></center></td></tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>

    </div> <!-- /container -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
  </body>
</html>
