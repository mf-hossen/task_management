<?php
// Application middleware

$mw = function ($request, $response, $next) {

    if (!isset($_SESSION['user'])) {
        //don't interfere with unmatched routes
        //var_dump($_SESSION);die();
        $route = $request->getAttribute('route');
        if ($route ) {
            return $response->withStatus(403)->withHeader('Location', '/login');
        }
    }

    return $next($request, $response);

};