<?php

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};

// config/routes.php
use App\Controller\BlogController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

//index.php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$request = Request::createFromGlobals();
$url = $request->getPathInfo();

switch ($url) {
    case '/':
        (new HomeController())->home();
        break;
    case '/admin':
        (new AdminController())->adminHome();
        break;
    default:
        $response = new Response();
        $response->setStatusCode(Response::HTTP_NOT_FOUND);
}

$response->send();

class HomeController{
    public function home() {
        return new Response("
        <h1>Home</h1>
        ");
    }
}

class AdminController{
    /**
     * Home admin route.
     * @return Response
     */
    public function adminHome() {
        return new Response("
        <h1>Welcome to the admin area</h1>");
    }
}