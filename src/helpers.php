<?php
/**
 * route group
 *
 * @param $arrParams
 * @param $callback
 * @return mixed
 */
function group(array $arrParams, closure $callback)
{
    return coreRouteGroup($arrParams, $callback);
}
/**
 * route get
 *
 * @param $url
 * @param $controller "ExampleController@index"
 * @return mixed
 */
function get(string $url, string $controller)
{
    return coreRouteGet($url, $controller);
}
/**
 * route post
 *
 * @param $url
 * @param $controller "ExampleController@store"
 * @return mixed
 */
function post(string $url, string $controller)
{
    return coreRoutePost($url, $controller);
}
/**
 * route put
 *
 * @param $url
 * @param $controller "ExampleController@update"
 * @return mixed
 */
function put(string $url, string $controller)
{
    return coreRoutePut($url, $controller);
}
/**
 * route patch
 *
 * @param $url
 * @param $controller "ExampleController@update"
 * @return mixed
 */
function patch(string $url, string $controller)
{
    return coreRoutePatch($url, $controller);
}
/**
 * route delete
 *
 * @param $url
 * @param $controller "ExampleController@delete"
 * @return mixed
 */
function delete(string $url, string $controller)
{
    return coreRouteDelete($url, $controller);
}

function responseJson($response, $code)
{
    http_response_code($code);
    header('Content-type: application/json');
    return json_encode($response);
}

/**
 * get all params in request
 *
 * @return mixed
 */
function requestParams()
{
    return coreRequestParams();
}
