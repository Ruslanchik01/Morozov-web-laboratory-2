<?php
//print_r($_SERVER['REQUEST_URI']);
//die();
session_start();
require_once 'config/db.php';
require_once 'route/web.php';

//define controller and action
$controllerName = isset($_GET['controller']) ? $_GET['controller'] : 'index';
$actionName = isset($_GET['action']) ? $_GET['action'] : 'index';

//завантажуємо об’єкт роутінга
$routing = new Route();
//завантажуємо об'єкт моделі
$db = new Db();

$routing->loadPage($db, $controllerName, $actionName);

