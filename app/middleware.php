<?php
// Application middleware

/*$mw = function ($request, $response, $next) {

    if (!isset($_SESSION['username'])) {
        //don't interfere with unmatched routes
        $route = $request->getAttribute('route');
        if ($route ) {
            return $response->withStatus(403)->withHeader('Location', '/login');
        }
    }

    return $next($request, $response);

};*/