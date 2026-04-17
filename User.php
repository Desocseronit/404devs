<?php namespace core;

class User{
    private $record;

    public function __construct($record){
        $this->record = $record;

        foreach($record->attributes as $key => $val) { 
            $this->$key = &$record->$key;
        }
    }

    public function __set($name , $value){
        $this->$name = &$this->attributes[$name];
        return $value;
    }

    public function _reg($username,$password){
        Database::instance()->query('INSERT into(user,password_hash,showname,auth_token' value ($user,$password,$showname,$token))
        $token = md5("$username",bin2hex(random_bytes(32)));
    }

    setcookie("_identify",$tokem,time()*60*60*24*14);
}
  


