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

    public function getInfo(){
        $vars = $this->record->stringify();
        $user = User::find($vars['user_id']);
        $vars['user'] = $user->stringify();
        $vars['category'] = Database::instance()->getOne('categories' , $vars['category_id'])->name;
        $vars['level'] = Database::instance()->getOne('levels' , $vars['level_id'])->name;
        $postImgs = $this->getImages();
        $vars['images'] = [];
        foreach($postImgs->items() as $img){
            $vars['images'][] = Image::findById($img->getValue()->img_id)->path;
        }
        unset($vars['user_id']);
        unset($vars['level_id']);
        unset($vars['category_id']);
        return json_encode($vars);
    }

    public static function paginate($page = 1 , $perPage = 20 , $filterBy = 'votes' , $sortSide = false , $sortSideId = false , $category = null, $level = null){
        $data = Database::instance()->paginate('posts' , $page , $perPage , $filterBy , $sortSide , $sortSideId , $category , $level);
        $newData = [];
        foreach($data['data']->items() as $post){
            $postInstance = new self($post->getValue());
            $newData[]=$postInstance->getInfo();
        }
        $data['data'] = $newData;
        return $data;
    }
}