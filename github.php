<?php
include('includes/load.php');

if($_POST) {
    $db = getMySQL();
    $name = $db->escape_string($_POST['name']);
    $repo = explode("@", $_POST['repo']);
    $repoid = intval($repo[0]);
    $reponame = $db->escape_string($repo[1]);
    if($repoid == '-1' || $reponame == '-1') {
        $repoid = "NULL";
        $reponame = "NULL";
    }else {
        $j = json_encode(["name"=>"web", "active"=>true, "events"=>["push"], "config"=>["url"=>"http://punchio.shdwlf.com/webhook.php","content_type"=>"json"]]);
        $a = explode("/", $reponame);
        $owner = $a[0];
        $repo = $a[1];
        $token = $db->query("SELECT * FROM github WHERE accountowner={$_SESSION['user']->id}")->fetch_object()->access_token;
        $ch = curl_init("https://api.github.com/repos/$owner/$repo/hooks?access_token=$token");
        $result = curl_exec($ch);
        curl_close($ch);
        $q = json_decode($result, true);
        $added = false;
        foreach($q as $k=>$v) {
            if($v["config"]["url"] == "http://punchio.shdwlf.com/webhook.php") {
                $added = true;   
            }
        }
        if(!$added) {
            $ch = curl_init("https://api.github.com/repos/$owner/$repo/hooks?access_token=$token");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $j);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'User-Agent: curl'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);
        }
        
        $reponame = "'".$reponame."'";
        
    }
    $id = intval($_GET['proj']);
    $db->query("UPDATE projects SET projectname='$name', githubrepo=$repoid, githubname=$reponame WHERE projectid=$id");
    header('location: /github.php?proj='.$id);
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
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
  <body>

    <?php getNavbar(); ?>
      
    <div class="container">
        
    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">GitHub Association</div>
                <div class="panel-body">
                <?php
                    $db = getMySQL();
                    $resx = $db->query("SELECT * FROM github WHERE accountowner={$_SESSION["user"]->id}");
                    if($resx->num_rows) {
                        //Added   
                        $d = $resx->fetch_object();
                        echo "<a style='cursor: pointer;' class='btn btn-success form-control'>{$d->username} linked!</a>";
                    }else {
                        //Not added
                        echo "<a href='/includes/oauth.php?action=login' class='btn btn-danger form-control'>Connect Your Github Account!</a>";
                    }
                ?>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">Projects</div>
                <div class="panel-body">
                    <?php
                        $db = getMySQL();
                        $id = intval($_GET['proj']);
                        $res = $db->query("SELECT * FROM projects WHERE projectowner={$_SESSION['user']->id} AND projectid=$id");   
                        $dat = $res->fetch_object();
                    ?>
                    <table class="table">
                        <form method="post" action="github.php?proj=<?php echo $_GET['proj']; ?>">
                        <tr>
                            <td style="vertical-align: middle;">Project Name</td>
                            <td><input class="form-control" type="text" autocomplete="off" value="<?php echo $dat->projectname; ?>" name="name"></td>
                        </tr>
                        <tr>
                            <td style="vertical-align: middle;">GitHub Repository</td>
                            <td>
                                <?php
                                    if($resx->num_rows) {
                                        ?>
                                            <select class="form-control" name="repo">
                                                <option value="-1@-1">No Repository</option>
                                                <?php
                                                    $r = $db->query("SELECT * FROM github WHERE accountowner={$_SESSION['user']->id}");
                                                    if($r->num_rows) {
                                                        $data = $r->fetch_object();
                                                        $username = $data->username;
                                                        $token = $data->access_token;
                                                        $d = getSslPage("https://api.github.com/user/repos?access_token=$token&type=owner");
                                                        $j = json_decode($d, true);
                                                        foreach($j as $k=>$v) {
                                                            echo "<option value='{$v['id']}@{$v['full_name']}'>{$v['full_name']}</option>";
                                                        }
                                                    }
                                                ?>
                                            </select>
                                            <?php
                                    }else {
                                        echo "<center>You must link your GitHub account first!</center>";   
                                    }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><input type="submit" class="btn btn-success pull-right"></td>
                        </tr>
                        </form>
                    </table>
                </div>
            </div>
        </div>
    </div>

    </div> <!-- /container -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
      <script>
                                                    <?php
                                                        $rid = $dat->githubrepo;
                                                        $rnam = $dat->githubname;
                                                        $i = $rid.'@'.$rnam;
                                                    ?>
                                                    $(function() {$('select option[value="<?php echo $i; ?>"]').attr('selected', 'selected');});
                                            
                                            </script>
  </body>
</html>
