<?php 

define('_ROOTPATH_', __DIR__);

spl_autoload_register(function ($className) {
    $filePath = _ROOTPATH_ . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';

    if (file_exists($filePath)) {
        require_once $filePath;
    }
});

session_start(); 

use App\Controller\Controller;

$controller = new Controller();
$controller->route();