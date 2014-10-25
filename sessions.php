<?php
include('includes/load.php');
if(!isset($_GET['proj']))
    header('location: /projects.php');
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
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
  <body>

    <?php getNavbar(); ?>
      
    <div class="container">
        
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <?php $proj = intval($_GET['proj']); $db = getMySQL(); $a = $db->query("SELECT * FROM projects WHERE projectid=$proj")->fetch_object(); ?>
                <div class="panel-heading">Sessions For <b><?php echo $a->projectname; ?></b> <i>All Times Are In EST</i></div>
                <table class="table" style="text-align: center;">
                    <tr>
                        <th style="text-align: center;">Begin</th>
                        <th style="text-align: center;">End</th>
                        <th style="text-align: center;">Session Length</th>
                        <th style="text-align: center;">Commits</th>
                        <th style="text-align: center;">Notes</th>
                    </tr>
                    <?php
                        $res = $db->query("SELECT * FROM actions WHERE project=$proj ORDER BY actionid ASC");
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
                                if($d->notefile == null)
                                    $data[] = [$big, $low];
                                else
                                    $data[] = [$big, $low, $d->notefile];
                                $tmp = [];
                            }
                        }

                        
                        if($g = $db->query("SELECT * FROM github WHERE accountowner={$_SESSION['user']->id}")->fetch_object()) {
                            $auth = $g->githubid;
                        }else {
                            $auth=-1;   
                        }
                        $rep = $a->githubrepo;
                        $name = $a->githubname;
                        
                        

                        $commits = Array();
                        foreach($data as $k=>$v) {
                            $res = $db->query("SELECT * FROM commits WHERE time >= {$v[1]} AND time <= {$v[0]} AND repositoryid='$rep' AND author=$auth");
                            $com = Array();
                            if($res->num_rows) {
                                while($d = $res->fetch_object()) {
                                    $com[] = $d->commitid;
                                }
                            }
                            $coms = "";
                            $i = 0;
                            foreach($com as $kk=>$vv) {
                                $coms .= "<a href='https://github.com/$name/commit/$vv'>".substr($vv, 0, 8)."</a> ";
                                $i += 1;
                                if($i == 3) {
                                    $coms .= "<br />";
                                    $i = 0;
                                }
                            }
                            
                            if(count($v) == 3) {
                                $notes = "<td><a href='http://punchio.shdwlf.com/notes/{$v[2]}'>Notes</a></td>";
                            }else {
                                $notes = "<td></td>";   
                            }
                            
                            echo "<tr><td>".date('m/d/Y H:i:s', $v[1])."</td><td>".date("m/d/Y H:i:s", $v[0])."</td><td>".formatSeconds($v[0]-$v[1])."</td><td>$coms</td>$notes</tr>";
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
