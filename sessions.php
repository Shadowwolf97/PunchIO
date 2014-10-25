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
                <div class="panel-heading">Sessions</div>
                <table class="table">
                    <tr>
                        <th>Begin</th>
                        <th>End</th>
                        <th>Total Time</th>
                        <th>Commits</th>
                    </tr>
                    <?php
                        $db = getMySQL();
                        $proj = intval($_GET['proj']);
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
                                $data[] = [$big, $low];
                                $tmp = [];
                            }
                        }

                        $rep = $db->query("SELECT * FROM projects WHERE projectid=$proj")->fetch_object()->githubrepo;
                        $auth = $db->query("SELECT * FROM github WHERE accountowner={$_SESSION['user']->id}")->fetch_object()->githubid;

                        $commits = Array();
                        foreach($data as $k=>$v) {
                            $res = $db->query("SELECT * FROM commits WHERE time >= {$v[1]} AND time <= {$v[0]} AND repositoryid='$rep' AND author='$auth'");
                            $com = "";
                            if($res->num_rows) {
                                $d = $res->fetch_object();
                                $com = $d->commitid;
                            }
                            echo "<tr><td>{$v[1]}</td><td>{$v[0]}</td><td>Soon</td><td>".substr($com, 0, 8)."</td></tr>";
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
