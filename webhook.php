<?php
include('includes/load.php');

$json = json_decode(file_get_contents('php://input'), true);
$repoid = $json["repository"]["id"];
$time = time();
$cid = $json["commits"][0]["id"];
$author = $json["commits"][0]["author"]["username"];
$db = getMySQL();
$res = $db->query("SELECT * FROM projects WHERE githubrepo=$repoid");
if($res->num_rows) {
    $res = $db->query("SELECT * FROM github WHERE username='$author'");
    if($obj = $res->fetch_object()) {
        $aid = $obj->githubid;
        $db->query("INSERT INTO commits (commitid, author, time, repositoryid) VALUES ('$cid', $aid, $time, $repoid)");
    }else {
        //Maybe github will stop sending us requests if we 4oh4? ;o
        http_response_code(404);
    }
}else {
    //Maybe github will stop sending us requests if we 4oh4? ;o
    http_response_code(404)
}