<?php

// content of mail body we pass to mail->send
use App\Classes\ErrorHandler;
use App\Classes\Session;

function make($filename, $data)
{

    extract($data);


    // turn on output buffering
    ob_start();

    // include template
    include __DIR__ . "/../../public/views/email/".$filename.'.php';

    // get content of the file
    $content = ob_get_contents();

    // erase the output and turn off output buffering
    ob_end_clean();

    return $content;
}

/**
 * Generate token for verification
 * @param $length
 * @return string
 * @throws Exception
 */
function generateToken($length)
{
    return bin2hex(random_bytes($length/2));
}

/**
 * check if user has permissions based on $permit and if he is logged in
 * @param int $permit | 0 = no access, | 1 = employee, | 2 = admin, | 3 = universal access
 * @throws Exception
 */
function checkPermissions($permit = 0)
{
    // check if user is logged in
    if(!Session::has('logged_in')){
        ErrorHandler::response('401');
    }

    //if user is logged in check if has permission to access content
    elseif(Session::get('user_type') !== $permit && $permit !== 3)
    {
        ErrorHandler::response('403');
    }
}