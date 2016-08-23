<?php
namespace App;
use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class Test{
/*    private $view;
    private $logger;

    public function __construct(Twig $view, LoggerInterface $logger)
    {
        $this->view = $view;
        $this->logger = $logger;
    }


    public  function A(Request $request, Response $response, $args){
        $this->view->render($response, 'home.twig');
        return $response;
        
    }*/
    public static function hello(){
        echo 'Hello World';
    }
}