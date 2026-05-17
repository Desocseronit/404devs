<?php namespace core;
/**
 * Экземпляр класса User представляет собой пользователя
 * @reg => регистрирует нового пользователя и заносит данные в бд
 * @authByCredentials => аутентификация по куки
 * @authByCookies => аутентификация по паролю
 * @changeAvatar => смена аватара
 * @logOut => выход из аккаунта
 * @delete => изменение статуса на удален
 * 
 */
class UserData {
    public function __construct(
        public readonly string $name,
        public readonly string $password,
        public readonly string $email,
    ) {}
}
class User{
    public $record;

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

    static public function reg(UserData $data){
        $hash_password = password_hash($data->password,PASSWORD_DEFAULT);
        $token = md5("$data->name" .bin2hex(random_bytes(32)));
        $userData = [];
        $userData['name'] = $data->name;
        $userData['show_name'] = $data->name;
        $userData['password_hash']= $hash_password;
        $userData['email'] = $data->email;
        $userData['created_data'] = date('Y-m-d H:i:s');
        $userData['auth_token']=$token;
        $userData['status_id'] = 1;
        $user = Database::instance()->insertRecord("users",$userData);
        setcookie("identify",$token,time()+60*60*24*14);
        return new self($user);
    }
    
    public static function authByCredentials($username,$password){
        $res = Database::instance()->getOne('users',$username,'name');
        if(!$res){
            return false; 
        }
        $userRecord = $res;
        if($userRecord->status_id == 4) return false;  
        if(password_verify($password,$userRecord->password_hash)){
            $newToken = bin2hex(random_bytes(32));
            Database::instance()->updateRecord(
                'users',
                ['auth_token'=>$newToken],
                'id=$1',
                [$userRecord->id]
            );
            setcookie("identify",$newToken,time()+60*60*24*14);
            return new self(Database::instance()->getOne('users',$username,'name')); 
        }
        return false;   
    }
    
    public static function authByCookies(){
        if(!isset($_COOKIE['identify'])){
            return false;
        }
        $token = $_COOKIE['identify'];
        $res = Database::instance()->getOne('users',$token,'auth_token');
        if($res) return new self($res);
    }

    public function changeAvatar($imgId){
        Database::instance()->updateRecord('users',[
            "avatar_id" => $imgId[0]
        ], 'id = $1' , [$this->id]);
    }

    public function getAvatar(){
        // $avatar = $this->avatar_id;
        return Image::findById($this->avatar_id);
    }

    public function logOut(){
        if($this->record && isset($this->record->id)){
            Database::instance()->updateRecord(
                'users',
                ['auth_token'=>null],
                'id = $1',
                [$this->record->id]
            );
        }
        setcookie("identify", "", time() - 3600);
       
        return true;
    }

    public function delete(){
        if($this->record && isset($this->record->id)){
            DataBase::instance()->updateRecord(
                'users',
                ['status_id' => 4],
                'id = $1',
                [$this->record->id]
            );
        }
       
        return true;
    }
    public function modify($newData){
        if(isset($newData['newAvatar'])){
            $this->changeAvatar($newData['newAvatar']);
            unset($newData['newAvatar']);
        }
        if(!empty($newData)){
            Database::instance()->updateRecord(
                'users',
                $newData,
                'id = $1',
                [$this->id]
            );
        }        
    }

    public static function find($id){
        return new self(Database::instance()->getOne('users',$id));
    }

    public static function findByLogin($name){
        $usersRecords = Database::instance()->selectRecord('users' , "*"  , [['name', 'ILIKE' , "%$name%"]]);
        $users = [];
        foreach($usersRecords->items() as $record){
            $users[] = new self($record->getValue());
        }
        return $users;
    }

    public static function findByShowName($name){
        $usersRecords = Database::instance()->selectRecord('users' , "*"  , [ empty($name) ? "TRUE" : "show_name ILIKE '%$name%'"]);
        $users = [];
        foreach($usersRecords->items() as $record){
            $users[] = new self($record->getValue());
        }
        return $users;
    }

    public function stringify($isPrivate = false){
        $vars = $this->record->stringify();
        if(isset($vars['avatar_id'])){
            $vars['avatar'] = Image::findById($vars['avatar_id'])->stringify();
            unset($vars['avatar_id']);
        }
        if($isPrivate) unset($vars['email']);
        unset($vars['password_hash']);
        unset($vars['auth_token']);
        return json_encode($vars);
    }

    public static function checkIfUserExist(UserData $data){
        $record = Database::instance()->selectRecord('users' , '*' , ['name = \''. $data->name . '\' OR email = \'' . $data->email.'\''])->items();
        if($record[0] && $record->{0}->getValue()->status_id == 4) return false;
        return (bool)$record;
    }
}
