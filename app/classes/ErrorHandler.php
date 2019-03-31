<?php

namespace App\Classes;

class ErrorHandler
{

    /**
     * output message to user
     * @param $view | file to view
     * @param $msg | message we want to output to user
     */
    public static function view($view, $msg)
    {
        $_SESSION['msg'] = $msg;
        require_once BASE_PATH.'/views/msg/' . $view . '.php';
        die();
    }

    /**
     * view response status code page
     * @param $view | response code - file name to render
     */
    public static function response($view)
    {

        http_response_code($view);
        require_once BASE_PATH.'/views/errors/' . $view . '.php';
        die();
    }

}