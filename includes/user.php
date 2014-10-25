<?php

class User {
 
    public $id;
    public $name;
    public $email;
    
    public function __construct($id, $name, $email) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
    }
    
    /**
    *   Load user from ID
    */
    public static function FromID($id) {
        //todo load the user ;)
        $db = getMySQL();
        $res = $db->query("SELECT * FROM users WHERE userid = $id");
        if($res === false)
            return null;
        $d = $res->fetch_object();
        $u = new User($d->userid, $d->name, $d->email);
        return $u;
    }
    
}