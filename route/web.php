<?php
class Route{
    function loadPage($db, $controllerName, $actionName = 'index'){
        include_once 'app/Controllers/IndexController.php';
        include_once 'app/Controllers/UsersController.php';

        switch ($controllerName) {
            case 'users':
                $controller = new UsersController($db);
                break;
            default:
                $controller = new IndexController($db);
        }
        // запускаємо необхідний метод
        $controller->$actionName();
    }
}
