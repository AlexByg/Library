<?php
abstract class Controller extends AbstractController
{
    protected $title;
    protected $meta_desc;
    protected $meta_key;
    protected $url_active;

    public function __construct()
    {
        parent::__construct(
            new View(Config::DIR_TMPL),
            new Message(Config::FILE_MESSAGES)
        );

        $this->url_active = URL::deleteGET(URL::current(), 'page');
    }

    public function action404()
    {
        header('HTTP/1.1 404 Not Found');
        header('Status: 404 Not Found');

        $this->title = 'Страница не найдена - 404';
        $this->meta_desc = 'Запрошенная страница не существует.';
        $this->meta_key = 'страница не найдена, страница не существует, 404';

        $pm = new PageMessage();
        $pm->header = 'Страница не найдена';
        $pm->text = 'К сожалению, запрошенная страница не существует. '.
            'Проверьте правильность ввода адреса.';

        $this->render($pm);
    }

    protected function accessDenied()
    {
        $this->title = 'Доступ закрыт!';
        $this->meta_desc = 'Доступ к данной странице закрыт.';
        $this->meta_key = 'доступ закрыт, доступ закрыт страница, '.
            'доступ закрыт страница 403';

        $pm = new PageMessage();
        $pm->header = 'Доступ закрыт!';
        $pm->text = 'У Вас нет прав доступа к данной странице.';

        $this->render($pm);
    }

    final protected function render($str)
    {
        $params = [];
        $params['head'] = $this->getHead();
        $params['header'] = $this->getHeader();
        $params['content'] = $str;
        $params['footer'] = new Footer();

        $this->view->render(Config::LAYOUT, $params);
    }

    protected function getHead()
    {
        $head = new Head();
        $head->title = $this->title;
        $head->meta('Content-Type', 'text/html; charset=utf-8', true);
        $head->meta('description', $this->meta_desc, false);
        $head->meta('keywords', $this->meta_key, false);
        $head->meta(
            'viewport',
            'width=device-width,
            initial-scale=1,
            shrink-to-fit=no',
            false
        );
        $head->favicon = '/favicon.ico';
        $head->css = ['/styles/main.css'];
        $head->js = ['/js/validator.js'];

        return $head;
    }

    protected function getHeader()
    {
        $items_menu = $this->is_it_an_admin? MenuDB::getAll():  MenuDB::getTopMenu();

        $header = new Header();
        $header->uri = $this->url_active;
        $header->items_menu = $items_menu;

        if (!$this->auth_user) {
            $header->message = $this->fp->getSessionMessage('auth');
            $header->action = URL::current('', true);
        }

        return $header;
    }

    protected function getHornav()
    {
        $hornav = new Hornav();
        $hornav->addData('Главная', URL::get(''));

        return $hornav;
    }

    final protected function getOffset($count_on_page)
    {
        return $count_on_page * ($this->getPage() - 1);
    }

    final protected function getPage()
    {
        $page = $this->request->page ?? 1;

        if ($page < 1) {
            $this->notFound();
        }

        return $page;
    }

    final protected function getPagination(
        $count_elements,
        $count_on_page,
        $url = false
    ) {
        $count_pages = ceil($count_elements / $count_on_page);
        $active = $this->getPage();
        if (($active > $count_pages) && ($active > 1)) {
            $this->notFound();
        }

        $pagination = new Pagination();

        if (!$url) {
            $url = URL::deletePage(URL::current());
        }

        $pagination->url = $url;
        $pagination->url_page = URL::addTemplatePage($url);
        $pagination->count_elements = $count_elements;
        $pagination->count_on_page = $count_on_page;
        $pagination->count_show_pages = Config::COUNT_SHOW_PAGES;
        $pagination->active = $active;

        return $pagination;
    }

    protected function authUser()
    {
        $login = '';
        $password = '';
        $redirect = false;

        if ($this->request->auth) {
            $login = $this->request->login;
            $password = $this->request->password;
            $redirect = true;
        }

        $user = $this->fp->auth('auth', 'UserDB', 'authUser', $login, $password);

        if ($user instanceof UserDB) {
            if ($redirect) {
                $this->redirect(URL::current());
            }

            return $user;
        }

        return null;
    }

    final protected function admCheck()
    {
        if ($this->auth_user) {
            if ($this->auth_user->is_admin) {
                return true;
            }

            return false;
        }
    }
}
