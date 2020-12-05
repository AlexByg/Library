<?php
abstract class AbstractObjectDB
{
    const TYPE_TIMESTAMP = 1;

    protected static $db = null;
    private $format_date = '';
    private $id = null;
    private $properties = [];
    protected $table_name = '';

    public function __construct($table_name, $format_date)
    {
        $this->table_name = $table_name;
        $this->format_date = $format_date;
    }

    public static function setDB($db)
    {
        self::$db = $db;
    }

    public function load($id)
    {
        $id = (int) $id;

        if ($id < 0) {
            return false;
        }

        $select = new Select(self::$db);
        $select = $select->from($this->table_name, $this->getSelectFields())
            ->where('`id` = '.self::$db->getSQ(), [$id]);
        $row = self::$db->selectRow($select);

        if (!$row) {
            return false;
        }

        if ($this->init($row)) {
            return $this->postLoad();
        }
    }

    public function init($row)
    {
        foreach ($this->properties as $key => $value) {
            $val = $row[$key];

            if ($value['type'] === self::TYPE_TIMESTAMP) {
                if (!is_null($val)) {
                    $val = strftime($this->format_date, $val);
                }
            }

            $this->properties[$key]['value'] = $val;
        }

        $this->id = $row['id'];

        return $this->postInit();
    }

    public function isSaved()
    {
        return $this->getID() > 0;
    }

    public function getID()
    {
        return (int) $this->id;
    }

    public function save()
    {
        $update = $this->isSaved();
        $commit = $update ? $this->preUpdate() : $this->preInsert();

        if (!$commit) {
            return false;
        }

        $row = [];

        foreach ($this->properties as $key => $value) {
            if ($value['type'] === self::TYPE_TIMESTAMP) {
                if (!is_null($value['value'])) {
                    $value['value'] = strtotime($value['value']);
                }
            }

            $row[$key] = $value['value'];
        }

        if (count($row) > 0) {
            if ($update) {
                $success = self::$db->update(
                    $this->table_name,
                    $row,
                    '`id` = '.self::$db->getSQ(),
                    [$this->getID()]
                );

                if (!$success) {
                    throw new Exception();
                }
            } else {
                $this->id = self::$db->insert($this->table_name, $row);

                if (!$this->id) {
                    throw new Exception();
                }
            }
        }

        if ($update) {
            return $this->postUpdate();
        }

        return $this->postInsert();
    }

    public function delete()
    {
        if (!$this->isSaved()) {
            return false;
        }

        if (!$this->preDelete()) {
            return false;
        }

        $success = self::$db->delete(
            $this->table_name,
            '`id` = '.self::$db->getSQ(),
            [$this->getID()]
        );

        if (!$success) {
            throw new Exception();
        }

        $this->id = null;

        return $this->postDelete();
    }

    public function __set($name, $value)
    {
        if (array_key_exists($name, $this->properties)) {
            $this->properties[$name]['value'] = $value;

            return true;
        } else {
            $this->$name = $value;
        }
    }

    public function __get($name)
    {
        if ($name == 'id') {
            return $this->getID();
        }

        return array_key_exists($name, $this->properties)?
            $this->properties[$name]['value']:
            null;
    }

    public static function buildMultiple($class, $data)
    {
        $ret = [];

        if (!class_exists($class)) {
            throw new Exception();
        }

        $test_obj = new $class();

        if (!$test_obj instanceof AbstractObjectDB) {
            throw new Exception();
        }

        foreach ($data as $row) {
            $obj = new $class();
            $obj->init($row);
            $ret[$obj->getID()] = $obj;
        }

        return $ret;
    }

    public static function getAll($count = false, $offset = false)
    {
        $class = get_called_class();

        return self::getAllWithOrder(
            $class::$table,
            $class,
            'id',
            true,
            $count,
            $offset
        );
    }

    public static function getCount()
    {
        $class = get_called_class();

        return self::getCountOnWhere($class::$table, false, false);
    }

    public static function getAllOnField(
        $table_name,
        $class,
        $field,
        $value,
        $order = false,
        $asc = true,
        $count = false,
        $offset = false
    ) {
        return self::getAllOnWhere(
            $table_name,
            $class,
            "`$field` = ".self::$db->getSQ(),
            [$value],
            $order,
            $asc,
            $count,
            $offset
        );
    }

    protected static function getCountOnWhere(
        $table_name,
        $where = false,
        $values = false
    ) {
        $select = new Select();
        $select->from($table_name, ['COUNT(id)']);

        if ($where) {
            $select->where($where, $values);
        }

        return self::$db->selectCell($select);
    }

    protected static function getAllWithOrder(
        $table_name,
        $class,
        $order = false,
        $asc = true,
        $count = false,
        $offset = false
    ) {
        return self::getAllOnWhere(
            $table_name,
            $class,
            false,
            false,
            $order,
            $asc,
            $count,
            $offset
        );
    }

    protected static function getAllOnWhere(
        $table_name,
        $class,
        $where = false,
        $values = false,
        $order = false,
        $asc = true,
        $count = false,
        $offset = false
    ) {
        $select = new Select();
        $select->from($table_name, '*');
        if ($where) {
            $select->where($where, $values);
        }

        $order ? $select->order($order, $asc) : $select->order('id');

        if ($count) {
            $select->limit($count, $offset);
        }

        $data = self::$db->select($select);

        return AbstractObjectDB::buildMultiple($class, $data);
    }

    protected function loadOnField($field, $value)
    {
        $select = new Select();
        $select->from($this->table_name, '*')
            ->where("`$field` = ".self::$db->getSQ(), [$value]);
        $row = self::$db->selectRow($select);

        if ($row) {
            if ($this->init($row)) {
                return $this->postLoad();
            }
        }

        return false;
    }

    protected function add($field, $validator, $type = null, $default = null)
    {
        $this->properties[$field] = [
            'value' => $default,
            'validator' => $validator,
            'type' => ($type === self::TYPE_TIMESTAMP) ?  $type : null
        ];
    }

    protected function preInsert()
    {
        return $this->validate();
    }

    protected function postInsert()
    {
        return true;
    }

    protected function preUpdate()
    {
        return $this->validate();
    }

    protected function postUpdate()
    {
        return true;
    }

    protected function preDelete()
    {
        return true;
    }

    protected function postDelete()
    {
        return true;
    }

    protected function postInit()
    {
        return true;
    }

    protected function preValidate()
    {
        return true;
    }

    protected function postValidate()
    {
        return true;
    }

    protected function postLoad()
    {
        return true;
    }

    public function getDate($date = false)
    {
        if (!$date) {
            $date = time();
        }

        return strftime($this->format_date, $date);
    }

    protected static function hash($str, $secret = '')
    {
        return md5($str.$secret);
    }

    private function getSelectFields()
    {
        $fields = array_keys($this->properties);
        array_push($fields, 'id');

        return $fields;
    }

    private function validate()
    {
        if (!$this->preValidate()) {
            throw new Exception();
        }

        $v = [];
        $errors = [];

        foreach ($this->properties as $key => $value) {
            $v[$key] = new $value['validator']($value['value']);
        }

        foreach ($v as $key => $validator) {
            if (!$validator->isValid()) {
                $errors[$key] = $validator->getErrors();
            }
        }

        if (count($errors) == 0) {
            if (!$this->postValidate()) {
                throw new Exception();
            }

            return true;
        } else {
            throw new ValidatorException($errors);
        }
    }
}
