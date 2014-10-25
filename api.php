<?php
require('includes/load.php');

function lastAction($user) {
    if(isset($data["clocked"])) {
        return $data["clocked"];   
    }else {
        $db = getMySQL();
        $res = $db->query("SELECT * FROM actions WHERE user=$user ORDER BY time DESC LIMIT 1"); //Pull last action from DB
        if($obj = $res->fetch_object())
            return ["id"=>$obj->actionid, "time"=>$obj->time, "project"=>$obj->project, "action"=>$obj->action];
        return ["action"=>0];
    }
}

if(isset($_GET["method"], $_GET["apikey"])) {
    if($_GET["method"] == "fetch") {
        $db = getMySQL();
        $res = $db->query("SELECT * FROM users WHERE apikey='{$_GET["apikey"]}'");
        if($d = $res->fetch_object()) {
            $user = $d->userid;
            $a = lastAction($user);
            if($a["action"] == 1) {
                die('true:'.$a["time"]);   
            }else {
                die('false');
            }
        }
    }else if($_GET["method"] == "out") {
        
        $db = getMySQL();
        $res = $db->query("SELECT * FROM users WHERE apikey='{$_GET["apikey"]}'");
        if($d = $res->fetch_object()) {
            $user = $d->userid;
            $time = time();
            $ii = lastAction($user)["project"];
            $fn = uniqid().".pdf";
            $res = $db->query("INSERT INTO actions (time, action, user, project, notefile) VALUES ($time, 0, $user, $ii, '$fn')");
        }
        
        $cmd = "pdftk /var/www/punchio.shdwlf.com/up/* cat output /var/www/punchio.shdwlf.com/notes/$fn";
        shell_exec($cmd);
        shell_exec("rm /var/www/punchio.shdwlf.com/up/*");
    }

}