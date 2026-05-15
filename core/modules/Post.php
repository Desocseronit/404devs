<?php namespace core;
/**
 * Экземпляр класса PostData это вспомогательный тип данных для валидации входных данных для создания поста
 */
class PostData {
    public function __construct(
        public readonly User $user,
        public readonly string $title,
        public readonly string $text,
        public readonly int $category_id,
        public readonly int $level_id,
        public readonly ?array $images_ids = null
    ) {}
}
/**
 * Экземпляр класса Post является представлением записи в бд из таблицы posts
 */
class Post{
    public $record;
    public $isLiked = false;

    private function __construct($record){
        $this->record = $record;
        foreach($record->attributes as $key => $val){ $this->$key = &$this->record->$key;}
    }

    public function __set($name , $value){
        $this->$name = &$this->record->$name;
        return $value;
    }

    public static function create(PostData $data){
        $postData = [];
        $postData['created_at'] = date('Y-m-d H:i:s');
        $postData['user_id'] = $data->user->id;
        $postData['title'] = $data->title;
        $postData['text'] = $data->text;
        $postData['votes'] = 0;
        $postData['views'] = 0;
        $postData['category_id'] = $data->category_id;
        $postData['level_id'] = $data->level_id;
        
        $post=Database::instance()->insertRecord("posts" , $postData);
        
        if(!empty($data->images_ids)){
            foreach($data->images_ids as $id){
                Database::instance()->insertRecord('post_images', ['post_id' => $post->id , 'img_id' => $id]);
            }
        }

        return new self($post);
    }

    public static function find($id){
        return new self(Database::instance()->getOne('posts' , $id));
    }

    public function delete(){
        Database::instance()->deleteRecord('answers' , 'post_id = $1' , [$this->id]);
        return Database::instance()->deleteRecord('posts' , 'id = $1' , [$this->id]);
    }

    public function addImage($imgId){
        return Database::instance()->insertRecord('post_images', ['post_id' => $this->id , 'img_id' => $imgId]);
    }

    public function getImages(){
        return Database::instance()->selectRecord('post_images' , 'img_id' , [['post_id', '=' , $this->id]]);
    }

    public function isAuthor($userId){
        return $this->userId == $userId;
    }

    public function getAnswersCount(){
        return Database::instance()->selectRecord('answers' , 'id' , [['post_id' , '=' , $this->id]])->length();
    }

    public function getInfo(){
        $vars = $this->record->stringify();
        if(Application::$user) $this->updateLikeStatus();
        else unset($vars['isLiked']);
        $user = User::find($vars['user_id']);
        $vars['user'] = $user->stringify();
        $vars['category'] = Database::instance()->getOne('categories' , $vars['category_id'])->name;
        $vars['level'] = Database::instance()->getOne('levels' , $vars['level_id'])->name;
        $postImgs = $this->getImages();
        $vars['images'] = [];
        $vars['answersCount'] = $this->getAnswersCount();
        foreach($postImgs->items() as $img){
            $vars['images'][] = Image::findById($img->getValue()->img_id)->path;
        }
        unset($vars['user_id']);
        unset($vars['level_id']);
        unset($vars['category_id']);
        return json_encode($vars);
    }

    public static function paginate($page = 1 , $perPage = 20 , $filterBy = 'votes' , $sortSide = false , $sortSideId = false , $category = null, $level = null , $userId = null , $title = null){
        $conditions = [];
        if($category){
            $conditions[] = ['category_id' , '=' , $category];
        }
        if($level){
            $conditions[] = ['level_id' , '=' , $level];
        }
        if($userId){
            $conditions[]=['user_id', '=' , $userId];
        }
        if($title){
            $conditions[]=['title', 'ILIKE' , '%'.$title.'%'];
        }
        $data = Database::instance()->paginate('posts' , $page , $perPage , $filterBy , $sortSide , $sortSideId , $conditions);
        $newData = [];
        foreach($data['data']->items() as $post){
            $postInstance = new self($post->getValue());
            $newData[]=$postInstance->getInfo();
        }
        $data['data'] = $newData;
        return $data;
    }

    public static function getRandomPostId(){
        $allPosts = Database::instance()->selectRecord('posts' , '*')->items();
        return $allPosts[array_rand($allPosts)]->getValue()->id;
    }

    public function incrementViews(){
        if(isset($_COOKIE["viewed_".$this->id])){
            return;
        }
        setcookie("viewed_".$this->id, true ,time()+60*60*24);
        return Database::instance()->incrementField('posts', 'views' , 1, 'id = $1', [$this->id]);
    }

    public function vote($val){
        Database::instance()->incrementField('posts', 'votes' , (int)$val, 'id = $1', [$this->id]);
        Database::instance()->insertRecord('voted_posts' , ['post_id' => $this->id , 'user_id' => Application::$user->id]);
        $this->updateLikeStatus();
        return $this->isLiked;
    }

    public function updateLikeStatus(){
        $this->isLiked = (bool)Database::instance()->selectRecord('voted_posts' , '*' , [['user_id' , '=' , Application::$user->id], ['post_id' , '=' , $this->id]])->items();
    }

    public static function getAllCategories(){
        return array_map(fn($element) => $element->getValue()->name, Database::instance()->selectRecord('categories' , 'name')->items());
    }
    public static function getAllLevels(){
        return array_map(fn($element) => $element->getValue()->name, Database::instance()->selectRecord('levels' , 'name')->items());
    }
}
