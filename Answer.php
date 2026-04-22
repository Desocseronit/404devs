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
}