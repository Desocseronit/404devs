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
}