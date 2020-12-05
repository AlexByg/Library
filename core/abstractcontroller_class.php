<?php
abstract class AbstractController
{
    protected $view;
    protected $request;
    protected $fp = null;
    protected $auth_user = null;
    protected $is_it_an_admin = false;
    protected $jsv = null;

    public function __construct($view, $message)
    {
        if (!session_id()) {
            session_start();
        }

        $this->view = $view;
        $this->request = new Request();
        $this->fp = new FormProcessor($this->request, $message);
        $this->jsv = new JSValidator($message);
        $this->auth_user = $this->authUser();
        $this->is_it_an_admin = $this->admCheck();

        if (!$this->access()) {
            $this->accessDenied();
            throw new Exception('ACCESS_DENIED');
        }
    }

    abstract protected function render($str);
    abstract protected function accessDenied();
    abstract protected function action404();

    protected function authUser()
    {
        return null;
    }

    protected function admCheck()
    {
        return false;
    }

    protected function access()
    {
        return true;
    }

    final protected function notFound()
    {
        $this->action404();
        exit;
    }

    final protected function redirect($url)
    {
        header("Location: $url");
        exit;
    }

    final protected function renderData($modules, $layout, $params = [])
    {
        if (!is_array($modules)) {
            return false;
        }

        foreach ($modules as $key => $value) {
            $params[$key] = $value;
        }

        return $this->view->render($layout, $params, true);
    }
}
