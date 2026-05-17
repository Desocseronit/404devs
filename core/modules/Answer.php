<?php namespace core;
/**
 * Экземпляр класса AnswerData это вспомогательный тип данных для валидации входных данных для создания ответа
 */
class AnswerData{
    public function __construct(
        public readonly User $user,
        public readonly Post $post, 
        public readonly string $text,
        public readonly ?array $images_ids = null
    ) {}
}
/**
 * Экземпляр класса Answer является представлением записи в бд из таблицы answers
 */
class Answer{
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

    public static function create(AnswerData $data){
        $answerData = [];
        $answerData['user_id'] = $data->user->id;
        $answerData['post_id'] = $data->post->id;
        $answerData['text'] = $data->text;
        $answerData['votes'] = 0;
        $answerData['created_at'] = date('Y-m-d H:i:s');

        $answer = Database::instance()->insertRecord("answers" , $answerData);
        
        if(!empty($data->images_ids)){
            foreach($data->images_ids as $id){
                Database::instance()->insertRecord('answer_images', ['answer_id' => $answer->id , 'img_id' => $id]);
            }
        }

        return new self($answer);
    }
    public static function find($id){
        return new self(Database::instance()->getOne('answers' , $id));
    }

    public function delete(){
        return Database::instance()->deleteRecord('answers' , 'id = $1' , [$this->id]);
    }

    public function getImages(){
        return Database::instance()->selectRecord('answer_images' , 'img_id' , [['answer_id', '=' , $this->id]]);
    }

    public function addImage($imgId){
        return Database::instance()->insertRecord('answer_images', ['answer_id' => $this->id , 'img_id' => $imgId]);
    }

    public function isAuthor($userId){
        return $this->userId == $userId;
    }

    public function getInfo(){
        $vars = $this->record->stringify();
        $user = User::find($vars['user_id']);
        $vars['user'] = $user->stringify(true);
        $ansewrImgs = $this->getImages();
        $vars['images'] = [];
        foreach($ansewrImgs->items() as $img){
            $vars['images'][] = Image::findById($img->getValue()->img_id)->path;
        }
        unset($vars['user_id']);
        return json_encode($vars);
    }

    public static function paginate($page = 1 , $perPage = 20 , $filterBy = 'votes' , $sortSide = false , $sortSideId = false , $post_id = null){
        $data = Database::instance()->paginate('answers' , $page , $perPage , $filterBy , $sortSide , $sortSideId , [['post_id' , '=' , $post_id]]);
        $newData = [];
        foreach($data['data']->items() as $answer){
            $answerInstance = new self($answer->getValue());
            $newData[]=$answerInstance->getInfo();
        }
        $data['data'] = $newData;
        return $data;
    }

    public function vote($val){
        Database::instance()->incrementField('answers', 'votes' , (int)$val, 'id = $1', [$this->id]);
        Database::instance()->insertRecord('voted_answers' , ['answer_id' => $this->id , 'user_id' => Application::$user->id]);
        $this->updateLikeStatus();
        return $this->isLiked;
    }

    public function updateLikeStatus(){
        $this->isLiked = (bool)Database::instance()->selectRecord('voted_answers' , '*' , [['user_id' , '=' , Application::$user->id], ['answer_id' , '=' , $this->id]])->items();
    }
}