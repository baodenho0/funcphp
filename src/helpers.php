<?php

function group(array $arrParams, closure $callback)
{
    return coreRouteGroup($arrParams, $callback);
}

function get(string $url, string $controller)
{
    return coreRouteGet($url, $controller);
}

//function post($url, $controller)
//{
//    return coreRoutePost($url, $controller);
//}
//
//function put($url, $controller)
//{
//    return coreRoutePut($url, $controller);
//}
//
//function patch($url, $controller)
//{
//    return coreRoutePatch($url, $controller);
//}
//
//function delete($url, $controller)
//{
//    return coreRouteDelete($url, $controller);
//}

function responseJson($response, $code)
{
    http_response_code($code);
    header('Content-type: application/json');
    return json_encode($response);
}

function request()
{
    return coreRequestsParams();
}
