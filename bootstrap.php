<?php   

    ini_set('display_errors',1);
    ini_set('display_startup_erros',1);
    error_reporting(E_ERROR);

    define(HOST, 'localhost');
    define(USUARIO, 'root');
    define(SENHA, 'root');
    define(BANCO, 'api');

    define(DS, DIRECTORY_SEPARATOR);
    define(DIR_APP, __DIR__);
    define(DIR_PROJETO, 'api');

    if (file_exists('autoload.php')){
        include 'autoload.php';
    }else {
        echo 'Erro ao incluir bootstrap'; exit;
    }