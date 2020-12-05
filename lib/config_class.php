<?php
abstract class Config
{
    const SITENAME = 'library.alexbyg.ru';
    const ADDRESS = 'http://library-gh.loc';
    const SECRET = 'iFx9gAm1';

    const DB_HOST = 'localhost';
    const DB_USER = 'root';
    const DB_PASSWORD = '';
    const DB_NAME = 'library-gh';
    const DB_PREFIX = 'buhtig_';
    const DB_SYM_QUERY = '?';

    const DIR_COVERS = '/images/covers/';
    const DIR_TMPL = 'tmpl/';

    const LAYOUT = 'main';
    const FILE_MESSAGES = 'text/messages.ini';

    const FORMAT_DATE = '%d.%m.%Y %H:%M:%S';

    const COUNT_BOOKS_ON_PAGE = 5;
    const COUNT_SHOW_PAGES = 5;

    const SEF_SUFFIX = '.html';

    const DEFAULT_COVER = 'default.png';
    const MAX_SIZE_IMG = 51200;
}
