<?php
include('includes/load.php');

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
}

function formatSeconds($sec) {
    $ret = Array();
    if($sec > 3600) {$ret["hour"] = floor($sec/3600); $sec = $sec % 3600;}
    if($sec > 60) {$ret["minute"] = floor($sec/60); $sec = $sec % 60;}
    if($sec > 0) {$ret["seconds"] = $sec;}else{$sec=0;}
    
    if($ret["hour"] < 10) { $ret["hour"] = sprintf("%02d", $ret["hour"]); }
    if($ret["minute"] < 10) { $ret["minute"] = sprintf("%02d", $ret["minute"]); }
    if($ret["seconds"] < 10) { $ret["seconds"] = sprintf("%02d", $ret["seconds"]); }
    
    return $ret["hour"].":".$ret["minute"].":".$ret["seconds"];
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
        <div class="col-md-6 col-md-offset-3">
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
                        echo "<tr><td>{$obj->projectname}</td><td style='text-align: center'>".(formatSeconds(totalTime($obj->projectid)))."</td><td style='text-align: right;'><a href='#'>View Sessions</a></td><td><center><a href='#'>X</a></center></td></tr>";
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