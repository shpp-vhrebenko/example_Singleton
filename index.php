<?php

function __autoload($classname){
    // проверяем 1-й символ
    switch ($classname[0])
    {
        // C_Base
        case 'C':
            include_once("classes/controllers/$classname.php");
            break;
        // M_Articles
        case 'M':
            include_once("classes/model/$classname.php");
    }
}

$action = 'action_';
$action .= (isset($_GET['act'])) ? $_GET['act'] : 'index';

switch ($_GET['c'])
{
    case 'articles':
        $controller = new C_Articles();
        break;
    case 'editor':
        $controller = new C_Editor();
        break;
    default:
        $controller = new C_Articles();
}

//header('Content-type: text/html; charset=utf-8');

$controller->request($action);