<?php
class Blog extends Module
{
    public function __construct()
    {
        parent::__construct();

        $this->add('books', null, true);
        $this->add('books_count');
        $this->add('books_count_text');
        $this->add('is_it_an_admin', false, false);
        $this->add('pagination');
    }

    public function getTmplFile()
    {
        return 'blog';
    }
}
