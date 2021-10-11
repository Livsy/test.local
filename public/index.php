<?php

/**
 * Контроллер по умолчанию
 */
define('COTROLLER_DEFAULT', 'Home');

define('CONTROLLER_DEFAULT_METHOD', 'defaultMethod');

define('CONFIG', realpath(__DIR__.'/../.env'));

session_start();

$params = parse_ini_file(CONFIG, true);

/**
 * Автозагрузка файлов
 */
$projectAutoloader = function ($class_name)  use ($params)
{
    foreach($params['autoload'] as &$item)
    {
        if(file_exists($path = $item.$class_name . '.php'))
        {
            include $path;
            break;
        }
    }
};
spl_autoload_register($projectAutoloader);

/**
 * Роутинг с переменными
 */
$request = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

/**
 * Приводим имя конроллера к общему виду
 */
$request[0] = (empty($request[0])) ? COTROLLER_DEFAULT : ucfirst($request[0]);

if(!class_exists($request[0]))
{
    header('HTTP/1.1 404 Not Found');
    exit;
}

$params['urlVars'] = [];
if(count($request) > 1 && strpos($request[count($request)-1], '=') !== false)
{
    $paramString = str_replace(';', "\n", urldecode($request[count($request)-1]));
    $params['urlVars'] = parse_ini_string($paramString);
}

$controller = new $request[0]($params);
$method = $request[1] ?? CONTROLLER_DEFAULT_METHOD;
$method = method_exists($controller, $method) ? $method : CONTROLLER_DEFAULT_METHOD;
$controller->$method();
















