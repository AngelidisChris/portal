<?php

use App\Classes\ErrorHandler;

class App
{
    protected $controller = 'HomeController';

    protected $method = 'index';

    protected $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();

        if (file_exists('../app/controllers/' . $url[0] . 'Controller.php'))
        {
            $this->controller = $url[0].'Controller';
            unset($url[0]);
        }

        else if ($url[0] != '')
        {
            ErrorHandler::response('404',[]);
        }


        require_once '../app/controllers/' . $this->controller . '.php';

        $this->controller = new $this->controller;

        if (isset($url[1]))
        {
            if(method_exists($this->controller, $url[1]))
            {
                $this->method = $url[1];
                unset($url[1]);
            }
            else{
                ErrorHandler::response('404',[]);
            }
        }

        $this->params = $url ? array_values($url) : [];


        call_user_func_array([$this->controller, $this->method], $this->params);

    }

    public function parseUrl()
    {
        if (isset($_GET['url']))
        {
            return $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
    }
}