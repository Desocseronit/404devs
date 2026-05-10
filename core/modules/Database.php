<?php namespace core;

/**
 * Экземпляр класса Database существует для взаимодействия с БД
 * Класс осуществляет возможность открыть только одно подключение к БД (Singleton)
 * 
 * @query($sql, $params = []) - отвечает за выполнение SQL запросов к БД через pg_query_params
 * 
 * @selectRecord($tablename, $attributes = '*', $condition = [], $limit = null) 
 * - отвечает за вызов команды SELECT
 * - вызов: (имя таблицы, 
 *           искомые атрибуты (массив полей / строка с названием ОДНОГО атрибута / '*'),
 *           условия для WHERE: [[имя_атрибута, оператор, значение]],
 *           лимит (число))
 * - пример: selectRecord('users', ['id', 'name'], [['age', '>', 18]], 10)
 * 
 * @insertRecord($tablename, $data)
 * - отвечает за вызов команды INSERT
 * - вызов: (имя таблицы, 
 *           ассоциативный массив [имя_атрибута => значение])
 * - пример: insertRecord('users', ['name' => 'John', 'age' => 25])
 * - возвращает: ID новой записи
 * 
 * @updateRecord($tablename, $data, $where, $whereParams = [])
 * - отвечает за вызов команды UPDATE
 * - вызов: (имя таблицы, 
 *           ассоциативный массив [имя_атрибута => значение],
 *           строка условия для WHERE с плейсхолдерами ('id = $1 AND age = $2'),
 *           массив значений для плейсхолдеров [5, 18])
 * - пример: updateRecord('users', ['name' => 'John'], 'id = $1', [1])
 * - возвращает: true/false
 * 
 * @deleteRecord($tablename, $where, $params = [])
 * - отвечает за вызов команды DELETE
 * - вызов: (имя таблицы,
 *           строка условия для WHERE с плейсхолдерами ('id = $1'),
 *           массив значений для плейсхолдеров [5])
 * - пример: deleteRecord('users', 'id = $1', [5])
 * - возвращает: true/false
 * 
 * @getOne($tablename, $id, $idField = 'id')
 * - отвечает за получение одной записи по ID (или другому полю)
 * - вызов: (имя таблицы,
 *           искомое значение,
 *           имя искомого атрибута (по умолчанию 'id'))
 * - пример: getOne('users', 5) - получить по id
 * - пример: getOne('users', 'john@example.com', 'email') - получить по email
 * - возвращает: Collection с одной записью (доступ через [0])
 * 
 * @incrementField($tablename, $field, $value, $where, $whereParams = [])
 * - отвечает за атомарное увеличение или уменьшение числового поля в БД
 * - вызов: (имя таблицы,
 *           имя числового поля для изменения,
 *           значение инкремента (положительное для увеличения, отрицательное для уменьшения),
 *           строка условия для WHERE с плейсхолдерами ('id = $1 AND age = $2'),
 *           массив значений для плейсхолдеров [5, 18])
 * - пример: incrementField('posts', 'votes', 1, 'id = $1', [1]) - увеличить votes на 1
 * - пример: incrementField('posts', 'votes', -1, 'id = $1', [1]) - уменьшить votes на 1
 * - пример: incrementField('users', 'rating', 10, 'level = $1 AND status = $2', [5, 'active']) - увеличить rating на 10
 * - возвращает: true/false
 *
 * @paginate($tablename, $page = 1, $perPage = 20, $sortBy = 'votes', $sortSide = false, $sortIdSide = false, $categoryId = null, $conditions = [])
 * - отвечает за получение постов с пагинацией и сортировкой
 * - вызов: (имя таблицы,
 *           номер страницы (по умолчанию 1),
 *           количество записей на странице (по умолчанию 20),
 *           поле для сортировки (по умолчанию 'votes'),
 *           направление сортировки основного поля: false = DESC, true = ASC (по умолчанию false),
 *           направление сортировки ID: false = DESC, true = ASC (по умолчанию false),
 *           ID категории для фильтрации (по умолчанию null),
 *           дополнительные условия для WHERE: [[имя_атрибута, оператор, значение]] (по умолчанию []))
 * - пример: paginate('posts', 1, 20, 'votes', false, false, 5) - получить 1-ю страницу,
 *           по 20 записей, сортировка по votes DESC, id DESC, фильтр по category_id = 5
 * - пример: paginate('posts', 2, 15, 'created_at', true, false, null, [['votes', '>', 10]]) - 
 *           получить 2-ю страницу, по 15 записей, сортировка по created_at ASC, id DESC,
 *           без фильтра по категории, с дополнительным условием votes > 10
 * - пример: paginate('posts', 1, 10, 'level_id', false, true, 3, [['views', '>', 100]]) -
 *           получить 1-ю страницу, по 10 записей, сортировка по level_id DESC, id ASC,
 *           фильтр по category_id = 3, и views > 100
 * - возвращает: ассоциативный массив с ключами:
 *   'data' - Collection объектов Record с записями,
 *   'current_page' - текущая страница,
 *   'per_page' - записей на страницу,
 *   'total' - общее количество записей,
 *   'total_pages' - общее количество страниц,
 *   'has_next' - true/false (есть ли следующая страница),
 *   'has_prev' - true/false (есть ли предыдущая страница),
 *   'sort_by' - поле по которому выполнена сортировка,
 *   'from' - номер первой записи на странице,
 *   'to' - номер последней записи на странице
 */
 

class Database{
    private static $_instance = null;
    private $_connection;
    static private $readOnly = ['categories' , 'global_categories' , 'levels' , 'rights' , 'role' , 'role_rights', 'role_user' , 'status'];

    private function __construct($connect_str) {
        $this->_connection = pg_connect($connect_str);
        if (!$this->_connection) {
            die('Connection error: ' . pg_last_error());
        }
    }

    public static function instance($connect_str = null) {
        if (self::$_instance === null) {
            if ($connect_str === null) {
                return null;
            }
            self::$_instance = new self($connect_str);
        }
        return self::$_instance;
    }

    public function connection(){
        return $this->_connection;
    }

    private function query($sql, $params = []) {
        return pg_query_params($this->connection(), $sql, $params);
    }

    //SELECT
    public function selectRecord($tablename , $attributes = '*' , $conditions = [], $limit = null){
        $tablename = $this->tableNameValidator($tablename);

        if($attributes != '*'){
            if(is_array($attributes)){
                $attributes = implode(',' , array_map([$this, 'fieldValidator'], $attributes));
            }
            elseif(is_string($attributes)){
                $attributes = $this->fieldValidator($attributes);
            }
        }

        $whereClause = '';
        $params = [];
        if (!empty($conditions)) {
            $whereData = $this->buildCondition($conditions);
            $whereClause = ' WHERE ' . $whereData['sql'];
            $params = $whereData['params'];
        }
        
        $limitClause = $limit ? ' LIMIT ' . (int)$limit : '';
        
        $sql = "SELECT id , $attributes FROM $tablename $whereClause $limitClause";
        $records = $this->query($sql, $params);
        $res = [];
        while ($record = pg_fetch_assoc($records)){
            $res[] = new Record($tablename , $record['id'] , $record);
        }
        $res = new Collection($res);
        return $res;


    }

    //INSERT
    public function insertRecord($tablename, $data) {
        $tablename = $this->tableNameValidator($tablename, true);
        
        $fields = array_keys($data);
        $fields = array_map([$this, 'fieldValidator'], $fields);
        
        $placeholders = [];
        for ($i = 1; $i <= count($fields); $i++) {
            $placeholders[] = '$' . $i;
        }
        
        $sql = "INSERT INTO $tablename (" . implode(', ', $fields) . ") 
                VALUES (" . implode(', ', $placeholders) . ")
                RETURNING *";  
        
        $result = $this->query($sql, array_values($data));
        
        $row = pg_fetch_assoc($result);
        return new Record($tablename , $row['id'] , $row);
    }

    // UPDATE
    public function updateRecord($tablename, $data, $where, $whereParams = []) {
        $tablename = $this->tableNameValidator($tablename, true);

        $setParts = [];
        $updateParams = [];
        $paramCounter = 1;

        foreach ($data as $field => $value) {
            $field = $this->fieldValidator($field);
            $setParts[] = "$field = $" . $paramCounter++;
            $updateParams[] = $value;
        }

        $whereWithParams = [];
        foreach ($whereParams as $index => $param) {
            $whereWithParams[] = '$' . ($paramCounter + $index);
        }

        $whereProcessed = $where;
        foreach ($whereParams as $index => $param) {
            $oldPlaceholder = '$' . ($index + 1);
            $newPlaceholder = '$' . ($paramCounter + $index);
            $whereProcessed = str_replace($oldPlaceholder, $newPlaceholder, $whereProcessed);
        }

        $allParams = array_merge($updateParams, $whereParams);
        $sql = "UPDATE $tablename SET " . implode(', ', $setParts) . " WHERE $whereProcessed";

        $result = $this->query($sql, $allParams);
        if($result){
            return true;
        } else return false;
    }
    
    // DELETE
    public function deleteRecord($tablename, $where, $params = []) {
        $tablename = $this->tableNameValidator($tablename, true);
        
        $sql = "DELETE FROM $tablename WHERE $where";
        
        if($this->query($sql, $params)){
            return true;
        }
        return false;
    }

    public function getOne($tablename, $id, $idField = 'id') {
        $tablename = $this->tableNameValidator($tablename);
        $idField = $this->fieldValidator($idField);
        
        $sql = "SELECT * FROM $tablename WHERE $idField = $1 LIMIT 1";
        $records = $this->query($sql, [$id]);
        $record = pg_fetch_assoc($records);
        if(!$record){
            return false;
        }
        $res = new Record($tablename , $record['id'] , $record);
        return $res;
    }

    public function incrementField($tablename, $field, $value, $where, $whereParams = []) {
        $tablename = $this->tableNameValidator($tablename, true);
        $field = $this->fieldValidator($field);

        $value = (int)$value;
        $sql = "UPDATE $tablename SET $field = $field + $value WHERE $where";

        $result = $this->query($sql, $whereParams);
        return $result ? $result: false;
    }

    public function paginate($tablename, $page = 1, $perPage = 20, $sortBy = 'votes',bool $sortSide = false , bool $sortIdSide = false , $categoryId = null, $conditions = []) {
        $tablename = $this->tableNameValidator($tablename);
        if (!$tablename) return false;

        $page = max(1, (int)$page);
        $perPage = max(1, (int)$perPage);
        $offset = ($page - 1) * $perPage;
        $sortSide = $sortSide ? 'ASC' : 'DESC';
        $sortIdSide = $sortIdSide ? 'ASC' : 'DESC';

        $orderBy = $sortBy .' '. $sortSide . ', id ' . $sortIdSide;

        $whereClause = '';
        $params = [];
        $paramCounter = 1;

        if ($categoryId) {
            $whereClause .= " category_id = $" . $paramCounter++;
            $params[] = $categoryId;
        }

        if (!empty($conditions)) {
            $conditionData = $this->buildCondition($conditions);
            if ($whereClause) {
                $whereClause .= " AND " . $conditionData['sql'];
            } else {
                $whereClause = $conditionData['sql'];
            }
            $params = array_merge($params, $conditionData['params']);
            $paramCounter += count($conditionData['params']);
        }

        $whereClause = $whereClause ? "WHERE $whereClause" : "";

        $sql = "SELECT * FROM $tablename 
                $whereClause 
                ORDER BY $orderBy 
                LIMIT $" . $paramCounter++ . " 
                OFFSET $" . $paramCounter++;

        $queryParams = array_merge($params, [$perPage, $offset]);
        $result = $this->query($sql, $queryParams);

        if (!$result) {
            return false;
        }

        $posts = [];
        while ($row = pg_fetch_assoc($result)) {
            $posts[] = new Record($tablename, $row['id'], $row);
        }
        $postsCollection = new Collection($posts);

        $countSql = "SELECT COUNT(*) as total FROM $tablename $whereClause";
        $countParams = $params;
        $countResult = $this->query($countSql, $countParams);
        $totalRow = pg_fetch_assoc($countResult);
        $total = (int)$totalRow['total'];

        return [
            'data' => $postsCollection,
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'total_pages' => ceil($total / $perPage),
            'has_next' => $page < ceil($total / $perPage),
            'has_prev' => $page > 1,
            'sort_by' => $sortBy,
            'from' => $offset + 1,
            'to' => min($offset + $perPage, $total)
        ];
    }

    private function tableNameValidator($tabName, $isWrite = false){
        if(in_array($tabName , Database::$readOnly) && $isWrite){
            return false;
        }
        return $tabName;
    }

    private function fieldValidator($field){
        if (!preg_match('/^[a-zA-Z0-9_\.]+$/', $field)) {
            return false;
        }
        return $field;
    }

    private function buildCondition($conditions) {
        $sqlParts = [];
        $params = [];
        $paramCounter = 1;
        
        foreach ($conditions as $condition) {
            if (is_string($condition)) {
                $sqlParts[] = $condition;
            } else if (is_array($condition) && count($condition) === 3) {
                $field = $this->fieldValidator($condition[0]);
                $operator = $condition[1];
                $sqlParts[] = "$field $operator $" . $paramCounter++;
                $params[] = $condition[2];
            } else {
                return false;
            }
        }
        
        return [
            'sql' => implode(' AND ', $sqlParts),
            'params' => $params
        ];
    }
}