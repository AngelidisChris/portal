<?php

if(!isset($_SESSION)) session_start();

// set display errors to 1 if we are in local env
if (getenv('APP_ENV') === 'local')
    ini_set('display_errors', 1);
else
    ini_set('display_errors', 0);

require_once __DIR__ . './_env.php';

require_once  __DIR__ . './../core/App.php';

require_once __DIR__ . './../core/Controller.php';