<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once './libs/response.php';

require_once './app/middlewares/session.auth.middleware.php';
require_once './app/middlewares/verify.auth.middleware.php';

require_once './app/controllers/auth.controller.php';
require_once './app/controllers/carrera.controller.php';
require_once './app/controllers/piloto.controller.php';
require_once './app/controllers/app.controler.php';

require_once 'app/models/resultado.model.php';
require_once 'app/models/piloto.model.php';


define('BASE_URL', '//' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . dirname($_SERVER['PHP_SELF']) . '/');


$res = new Response();

$action = 'home'; // acción por defecto

if (!empty($_GET['action'])) {
    $action = $_GET['action'];
}


$params = explode('/', $action);

switch ($params[0]) {
    case 'home':
        sessionAuthMiddleware($res);
        $controller = new AppController();
        $controller->showHome();
        break;
    case 'mostrarLogin':
        $controller = new Authcontroller();
        $controller->showLogin();
        break;

    case 'login':
        $controller = new Authcontroller();
        $controller->login();
        break;

    case 'logout':
        $controller = new Authcontroller();
        $controller->logout();
        break;

    case 'admin':
        sessionAuthMiddleware($res); // verificamos que haya sesión
        $controller = new AuthController();
        $controller->showAdmin();
        break;

    case 'carreras':
        sessionAuthMiddleware($res);
        $controller = new CarreraController();
        $controller->showCarreras();
        break;

    case 'carrera':
        sessionAuthMiddleware($res);
        $carreraModel = new CarreraModel();
        $resultadoModel = new ResultadoModel();
        $view = new CarreraView();
        $controller = new CarreraController($carreraModel, $resultadoModel, $view);

        $id = isset($params[1]) ? intval($params[1]) : null;
        $controller->showCarrera($id);
        break;

    case 'pilotos':
        sessionAuthMiddleware($res);
        $controller = new PilotoController();
        $controller->showPilotos();
        break;

    case 'piloto':
        sessionAuthMiddleware($res);

        $pilotoModel = new PilotoModel();
        $resultadoModel = new ResultadoModel();
        $view = new PilotoView();


        $controller = new PilotoController($pilotoModel, $resultadoModel, $view);

        $id = isset($params[1]) ? intval($params[1]) : null;

        $controller->showPiloto($id);
        break;

    case 'pilotoNuevo':
        sessionAuthMiddleware($res);
        $controller = new PilotoController();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->createPiloto(); // procesa el POST
        } else {
            $controller->showFormPiloto(); // muestra el form vacío
        }
        break;


    default:
        echo "404 - Página no encontrada";
        break;
}
