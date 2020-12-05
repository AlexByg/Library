<?php
class Book extends ModuleHornav
{
    public function __construct()
    {
        parent::__construct();

        $this->add('book');
        $this->add('is_it_an_admin', false, false);
    }

    public function getTmplFile()
    {
        return 'book';
    }
}
