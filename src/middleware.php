<?php

$kernel = require_once '../middleware/kernel.php';

$getMiddlewareFiles = function() use ($kernel)
{
    $middleware = $kernel['middleware'];
    $middlewareGroups = $kernel['middlewareGroups'];
    $routeMiddleware = $kernel['routeMiddleware'];
    $middlewareRoute = coreRouteGetMiddleware();

    foreach ($middlewareRoute as $value) {
        if(isset($middlewareGroups[$value]) && $middlewareGroups[$value]) {
            foreach ($middlewareGroups[$value] as $valueInGroup) {
                $middleware[] = $valueInGroup;
            }
        }

        if(isset($routeMiddleware[$value]) && $routeMiddleware[$value]) {
            $middleware[] = $routeMiddleware[$value];
        }
    }

    return array_values(array_unique($middleware));
};

$showMiddleware = function ($content)
{
    if($content) {
        echo $content;
    } else {
        echo responseJson(['status' => 401, 'msg' => '401 Unauthorized']);
    }

    return false;
};
/**
 * hanlde middleware
 * 
 * @return bool
 */
$handle = function() use ($getMiddlewareFiles, $kernel, $showMiddleware)
{
    $middlewareFiles = $getMiddlewareFiles();

    foreach ($middlewareFiles as $file) {
        require_once $file . '.php';

        $resultMiddleware = $handle('next');
        if($resultMiddleware !== 'next') {
            return $showMiddleware($resultMiddleware);
        }
    }
    
    return true;
};

return compact(
    'handle',
    'showMiddleware'
);
