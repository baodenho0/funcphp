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
 * @param $more "[ 'middleware' => [] ]"
 * @return mixed
 */
function get(string $url, string $controller, array $more = [])
{
    return coreRouteGet($url, $controller, $more);
}
/**
 * route post
 *
 * @param $url
 * @param $controller "ExampleController@store"
 * @param $more "[ 'middleware' => [] ]"
 * @return mixed
 */
function post(string $url, string $controller, array $more = [])
{
    return coreRoutePost($url, $controller, $more);
}
/**
 * route put
 *
 * @param $url
 * @param $controller "ExampleController@update"
 * @param $more "[ 'middleware' => [] ]"
 * @return mixed
 */
function put(string $url, string $controller, array $more = [])
{
    return coreRoutePut($url, $controller, $more);
}
/**
 * route patch
 *
 * @param $url
 * @param $controller "ExampleController@update"
 * @param $more "[ 'middleware' => [] ]"
 * @return mixed
 */
function patch(string $url, string $controller, array $more = [])
{
    return coreRoutePatch($url, $controller, $more);
}
/**
 * route delete
 *
 * @param $url
 * @param $controller "ExampleController@delete"
 * @param $more "[ 'middleware' => [] ]"
 * @return mixed
 */
function delete(string $url, string $controller, array $more = [])
{
    return coreRouteDelete($url, $controller, $more);
}

function responseJson($response, int $code = 200)
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

/**
 * import file
 *
 * @param string $file
 * @return mixed
 */
function import(string $file)
{
    $result = require_once $file;

    return $GLOBALS['required'][$file];
}

/**
 * export file
 *
 * @param string $currentFile
 * @param array $export
 */
function export(string $currentFile, array $export)
{
    return $GLOBALS['required'][$currentFile] = $export;
}

