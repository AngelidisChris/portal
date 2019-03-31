<?php

define('BASE_PATH', realpath(__DIR__ . '/../../'));

$dotenv = Dotenv\Dotenv::create(BASE_PATH);

$dotenv->load();