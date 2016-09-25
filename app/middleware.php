<?php
// Application middleware

$mw = function ($request, $response, $next) {

    if (!isset($_SESSION['user'])) {
        //var_dump($_SESSION);die();
        $route = $request->getAttribute('route');
        if ($route ) {
            return $response->withStatus(403)->withHeader('Location', '/login');
        }
    }
    return $next($request, $response);

};

$mwAdmin = function ($request, $response, $next) {
   // var_dump($_SESSION['user'][0]['role']); die();
    if ($_SESSION['user'][0]['role'] != 'Admin') {
       // var_dump($_SESSION);die();
        $route = $request->getAttribute('route');
        if ($route ) {
            return $response->withStatus(403)->withHeader('Location', '/');
        }
    }
    return $next($request, $response);

};