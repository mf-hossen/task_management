<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


require "routes/task.php";
require "routes/user.php";



$app->get('/', function (Request $request, Response $response) {
    return $this->view->render($response, 'welcome.twig');
});





