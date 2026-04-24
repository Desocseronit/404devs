 
<?php namespace core;
/**
 * Экземпляр класса User представляет собой пользователя
 * @reg => регестрирует нового пользователя и заносит данные в бд
 * @authByCredentials => аутентификация по куки
 * @authByCookies => аутентификация по паролю
 */
class User{
    private $record;

    private function __construct($record){
        $this->record = $record;

        foreach($record->attributes as $key => $val) { 
            $this->$key = &$record->$key;

        }
    }

    public function __set($name , $value){
        $this->$name = &$this->attributes[$name];
        return $value;
    }

    static public function reg($username,$password,$email){
        $hash_password = md5($password);
        $token = md5("$username",bin2hex(random_bytes(32)));
        $record = Database::instance()->insertRecord('user',[
            'name'=>$username,
            'password_hash'=>$hash_password,
            'email'=>$email
        ]);
        if($record){
            Database::instance()->updateRecord(
                'user',
                ['auth_token'=>$token],
                'id=$1',
                [$record->id]
            );
            setcookie("identify",$token,time()+60*60*24*14);
        }
        $user = new User(Database::instance->selectRecord('user', '*' , [['id', '=' , $record->id]]));
        return $user;
    }
    
    public function authByCredentials($username,$password){
        $res = Database::instance()->selectRecord('user','*',[['username','=',$username]],1);
        if (isset($res->{0})){
            $userRecord = $res->{0}; 
        } 
        if(password_verify($password,$userRecord->password_hash)){
            $newToken = bin2hex(random_bytes(32));
            Database::instance()->updateRecord(
                'user',
                ['auth_token'=>$newToken],
                'id=$1',
                [$userRecord->id]
            );
            setcookie("identify",$newToken,time()+60*60*24*14);
            $this->record = $userRecord;
            return true;
        }
        return false;        
    }
    
    public function authByCookies(){
        if(!isset($_COOKIE['identify'])){
            return false;
        }
        $token = $_COOKIE['identify'];
        $res = Database::instance()->selectRecord('user','*',[['auth_token','=',$token]],1);
        return $res;
    }
     public function changeAvatar(Image $img){
        $this->id::instance()->insertRecord('user_avatar',[
            'user_id' => $this->id,
            "avatar" => $img->path
        ]);
    }
 public static function find($id){
  return new self(Database::instance()->getOne('users',$id));
 }
}
   
