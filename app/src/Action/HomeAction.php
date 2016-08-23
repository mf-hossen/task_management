<?php
namespace App\Action;

use App\StudentMapper;
use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

final class HomeAction
{
    private $view;
    private $logger;
    private  $db;
    public function __construct(Twig $view, LoggerInterface $logger , $db)
    {
        $this->view = $view;
        $this->logger = $logger;
        $this->db = $db;
    }

    public function __invoke(Request $request, Response $response, $args)
    {
        $this->logger->info("Home page action dispatched");
         $a = 10;
        $students =  new StudentMapper($this->db);
        $allStudent = $students->getStudent();
        var_dump($allStudent); die();
            $this->view->render($response, 'another.twig' , ['allStudent' => $allStudent]);
        return $response;
    }


}
