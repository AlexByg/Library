<?php
class BookDB extends ObjectDB
{
    protected static $table = 'books';

    public function __construct()
    {
        parent::__construct(self::$table);

        $this->add('cover', 'ValidateIMG', null, null);
        $this->add('title', 'ValidateTitle');
        $this->add('annotation', 'ValidateAnnotation');
        $this->add('author', 'ValidateAuthor');
        $this->add('link', 'ValidateURL');
        $this->add('year', 'ValidateYear');
        $this->add('date', 'ValidateDate', self::TYPE_TIMESTAMP, $this->getDate());
        $this->add('meta_desc', 'ValidateMD');
        $this->add('meta_key', 'ValidateMK');
    }

    protected function postInit()
    {
        if (!($this->cover)) {
            $this->cover = Config::DEFAULT_COVER;
        }

        $this->cover = Config::DIR_COVERS.$this->cover;

        return true;
    }

    protected function preValidate()
    {
        if ($this->cover == Config::DIR_COVERS.Config::DEFAULT_COVER) {
            $this->cover = null;
        }

        if (!is_null($this->cover)) {
            $this->cover = basename($this->cover);
        }

        return true;
    }

    public function getCover()
    {
        $cover = basename($this->cover);
        if ($cover != Config::DEFAULT_COVER) {
            return $cover;
        }

        return null;
    }

    public static function getAllShow($count = false, $offset = false)
    {
        $select = self::getBaseSelect();
        $select->order('date', false);

        if ($count) {
            $select->limit($count, $offset);
        }

        $data = self::$db->select($select);
        $books = ObjectDB::buildMultiple(__CLASS__, $data);

        return $books;
    }

    private static function getBaseSelect()
    {
        $select = new Select(self::$db);
        $select->from(self::$table, '*');

        return $select;
    }

    public static function existsOtherSameLink($link, $id)
    {
        $select = self::getBaseSelect();
        $select->where('`id` != '.self::$db->getSQ(), [$id])
            ->where('`link` = '.self::$db->getSQ(), [$link]);;

        return self::$db->selectCell($select);
    }

    public static function existsLink($link)
    {
        $select = new Select(self::$db);
        $select->from(self::$table, ['link'])
            ->where('`link` = '.self::$db->getSQ(), [$link]);

        return self::$db->selectCell($select);
    }
}
