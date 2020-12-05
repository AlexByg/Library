<?php
class MenuDB extends ObjectDB
{
    protected static $table = 'menu';

    public function __construct()
    {
        parent::__construct(self::$table);

        $this->add('title', 'ValidateTitle');
        $this->add('link', 'ValidateURL');
        $this->add('state', 'ValidateBoolean', null, 1);
    }

    public static function getTopMenu()
    {
        return ObjectDB::getAllOnField(self::$table, __CLASS__, 'state', 1);
    }
}
