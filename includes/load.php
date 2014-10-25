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