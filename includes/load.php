<?php
//Auto create a PHP session.
require('user.php');
session_start();

//Autoload all of the nessecary PHP classes.
require('config.php');
require('functions.php');

if(isset($_SESSION["state"], $_SESSION["access_token"])) {
    if(isLoggedIn()) {
        $db = getMySQL();
        $token = $db->escape_string($_SESSION["access_token"]);
        $state = $db->escape_string($_SESSION["state"]);
        $res = $db->query("SELECT * FROM github WHERE access_token='$token' AND state='$state'");
        if($res->num_rows === false || $res->num_rows == 0) {
            $data = getSslPage("https://api.github.com/user?access_token={$_SESSION["access_token"]}");
            $data = json_decode($data, true);
            $res = $db->query("INSERT INTO github (access_token, state, accountowner, username, legitid) VALUES ('$token', '$state', '{$_SESSION["user"]->id}', '{$data["login"]}', '{$data["id"]}')");
            unset($_SESSION['state']);
            unset($_SESSION['access_token']);
        }
    }
}

ini_set('display_errors', 'On');
error_reporting(E_ALL);


function getSslPage($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_REFERER, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
       'User-Agent: curl', 
    ));
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function formatSeconds($sec) {
    $ret = Array();
    if($sec > 3600) {$ret["hour"] = floor($sec/3600); $sec = $sec % 3600;}else {$ret["hour"] = 0;}
    if($sec > 60) {$ret["minute"] = floor($sec/60); $sec = $sec % 60;}else {$ret["minute"] = 0;}
    if($sec > 0) {$ret["seconds"] = $sec;}else{$ret["seconds"]=0;}
    
    if($ret["hour"] < 10) { $ret["hour"] = sprintf("%02d", $ret["hour"]); }
    if($ret["minute"] < 10) { $ret["minute"] = sprintf("%02d", $ret["minute"]); }
    if($ret["seconds"] < 10) { $ret["seconds"] = sprintf("%02d", $ret["seconds"]); }
    
    return $ret["hour"].":".$ret["minute"].":".$ret["seconds"];
}
