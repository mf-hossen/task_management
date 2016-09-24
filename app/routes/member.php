<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->group('/member', function () {
    $this->get('/create', function (Request $request, Response $response) {
        if ($_SESSION['user'][0]['role'] == 'Admin') {
            $msg = $this->flash->getMessages();

            return $this->view->render($response, 'admin/member/create.twig', ['message' => $msg]);
        } else {
            $this->flash->addMessage('error', 'Warning!!! You are not authorized to this page');

            return $response->withStatus(302)->withHeader('Location', '/');
        }

    });

    $this->post('/add', function (Request $request, Response $response) {

        $data = $request->getParsedBody();
        $map = new \App\UserMapper($this->db);
        $check_user = $map->checkUserName($data);
        if ($check_user == true) {
            $this->flash->addMessage('error', 'Username Already exists');

            return $response->withStatus(302)->withHeader('Location', '/member/create');
        }

        if (strlen($data['password']) >= 6) {

            if ($data['password'] == $data['confirm_password']) {
                $mapper = new \App\UserMapper($this->db);
                $lastID = $mapper->createUser($data);
                $this->flash->addMessage('success', 'New Member has beed created!!');

                return $response->withStatus(302)->withHeader('Location', '/member/list');
            } else {
                $this->flash->addMessage('error', 'Password don\'t match');

                return $response->withStatus(302)->withHeader('Location', '/member/create');
            }
        } else {
            $this->flash->addMessage('error', 'Password should be minimume six character');

            return $response->withStatus(302)->withHeader('Location', '/member/create');
        }


    });

    $this->get('/list', function (Request $request, Response $response) {
        if ($_SESSION['user'][0]['role'] == 'Admin') {
            $msg = $this->flash->getMessages();
            $mapper = new \App\UserMapper($this->db);
            $data = $mapper->memberList();

            //$this->flash->addMessage('success', 'Warning!!! You are not authorized to this page');
            return $this->view->render($response, 'admin/member/list.twig', ['data' => $data, 'message' => $msg]);
        } else {
            $this->flash->addMessage('error', 'Warning!!! You are not authorized to this page');

            return $response->withStatus(302)->withHeader('Location', '/');
        }
    });


    $this->get('/list/{id}', function (Request $request, Response $response) {
        $id = $request->getAttribute('id');
        echo 'success ' . $id;
    });

    $this->get('/edit/{id}', function (Request $request, Response $response) {
        if ($_SESSION['user'][0]['role'] == 'Admin') {
            $id = $request->getAttribute('id');
            $mapper = new \App\UserMapper($this->db);
            $data = $mapper->memberListById($id);

            return $this->view->render($response, 'admin/member/edit.twig', ['data' => $data]);
        } else {
            $this->flash->addMessage('error', 'Warning!!! You are not authorized to this page');

            return $response->withStatus(302)->withHeader('Location', '/');
        }
    });

    $this->post('/update', function (Request $request, Response $response) {
        if ($_SESSION['user'][0]['role'] == 'Admin') {
            $data = $request->getParsedBody();
            $mapper = new \App\UserMapper($this->db);
            $u = $mapper->memberEdit($data);
            if ($u == true) {
                $this->flash->addMessage('success', 'Member has updated successfully!!');

                return $response->withStatus(302)->withHeader('Location', '/member/list');
            }
        } else {
            $this->flash->addMessage('error', 'Warning!!! You are not authorized to this page');

            return $response->withStatus(302)->withHeader('Location', '/');
        }
    });

    $this->get('/delete/{id}', function (Request $request, Response $response) {
        if ($_SESSION['user'][0]['role'] == 'Admin') {
            $id = $request->getAttribute('id');
            $mapper = new \App\UserMapper($this->db);
            $u = $mapper->memberDelete($id);
            if ($u == true) {
                $this->flash->addMessage('error', ' Member has beed deleted!!');

                return $response->withStatus(302)->withHeader('Location', '/member/list');
            }
        } else {
            $this->flash->addMessage('error', 'Warning!!! You are not authorized to this page');

            return $response->withStatus(302)->withHeader('Location', '/');
        }
    });
})->add($mw);