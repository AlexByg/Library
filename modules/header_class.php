<?php
class Header extends Module
{
    public function __construct()
    {
        parent::__construct();

        $this->add('uri');
        $this->add('items_menu', null, true);
        $this->add('action');
        $this->add('message');
    }

    public function getTmplFile()
    {
        return 'header';
    }
}
