<?php
/**
 * array params {id}
 */
$params = [];
/**
 * namespace in route group
 */
$namespace = '';
/**
 * prefix in route group
 */
$prefix = '';
/**
 * middleware in route group
 */
$middleware = [];
/**
 * checked url in route
 */
$checkedUrlInRoutes = '';
/**
 * group stack
 */
$groupStack = [];

/**
 * route group
 *
 * @param $arrParams
 * @param $callback
 * @return mixed
 */
function coreRouteGroup(array $arrParams, closure $callback)
{
    if(coreRouteGetCheckedUrlInRoutes()) {
        return false;
    }

    coreRouteAddGroupStack($arrParams);
    coreRouteHandleGroupStack();

    $args = func_get_args();
    call_user_func_array($callback, $args);

    coreRouteUnsetLastGroupStack();
}

function coreRouteAddGroupStack($arrParams)
{
    if(!coreRouteGetCheckedUrlInRoutes()) {
        $GLOBALS['groupStack'][] = $arrParams;
    }
}

function coreRouteUnsetLastGroupStack()
{
    if(!coreRouteGetCheckedUrlInRoutes()) {
        array_pop( $GLOBALS['groupStack']);
    }
}

function coreRouteGetGroupStack()
{
    return $GLOBALS['groupStack'];
}

/**
 * route get
 *
 * @param $url
 * @param $controller "ExampleController@index"
 * @return mixed
 */
function coreRouteGet($url, $controller)
{
    if(coreRouteCheckUrl($url)) {
        coreRouteDispatch($controller);
    }
}

function coreRouteArrUrlInRoute($url)
{
    if($prefix = coreRouteGetPrefix()) {
        $url = $prefix . '/' . $url;
    }

    return explode('/', $url);
}

function coreRouteGetArrQueryUrl()
{
    return explode('/', coreRequestQueryURL());
}

function coreRouteCheckUrl($url)
{
    if(coreRouteGetCheckedUrlInRoutes()) {
        return false;
    }

    coreRouteSetDefaultParamsInUrl();

    $queryUrl = coreRouteGetArrQueryUrl();
    $urlInRoutes = coreRouteArrUrlInRoute($url);

    if(count($queryUrl) != count($urlInRoutes)) {
        return false;
    }

    foreach ($queryUrl as $key => $value) {
        if(preg_match('/\{(.*?)\}/', $urlInRoutes[$key], $match)) {

            coreRouteSetParamsInUrl($match[1], $queryUrl[$key]);

            continue;
        }

        if($value != $urlInRoutes[$key]) {
            return false;
        }
    }

    coreRouteSetCheckedUrlInRoutes(implode('/', $urlInRoutes));

    return true;
}

/**
 * get array params {id}
 *
 * @return array
 */
function coreRouteGetParamsInUrl() : array
{
    return $GLOBALS['params'];
}

function coreRouteSetParamsInUrl($key, $value)
{
    $GLOBALS['params'][$key] = $value;
}

function coreRouteSetDefaultParamsInUrl()
{
    $GLOBALS['params'] = [];
}

function coreRouteSetNamespace(string $namespace)
{
    $GLOBALS['namespace'] = $namespace;
}

/**
 * get namespace in route group
 *
 * @return string
 */
function coreRouteGetNamespace() : string
{
    return $GLOBALS['namespace'];
}

function coreRouteSetPrefix(string $prefix)
{
    $GLOBALS['prefix'] = $prefix;
}

/**
 * get prefix in route group
 *
 * @return string
 */
function coreRouteGetPrefix() : string
{
    return $GLOBALS['prefix'];
}

function coreRouteSetMiddleware(array $middleware)
{
    $GLOBALS['middleware'] = $middleware;
}

function coreRouteGetMiddleware() : array
{
    return $GLOBALS['middleware'];
}

/**
 * @param string $controller "ExampleController@index"
 * @return array
 */
function coreRouteHandleController(string $controller)
{
    $explode = explode('@', $controller);

    if(!isset($explode[0]) || !isset($explode[1]) || !$explode[0] || !$explode[1]) {
        error('Error: ' . coreRouteGetCheckedUrlInRoutes() . ' | ' . $controller);
    }

    return [
        'controller' => $explode[0],
        'method' => $explode[1],
    ];
}

function coreRouteSetCheckedUrlInRoutes(string $url)
{
    $GLOBALS['checkedUrlInRoutes'] = $url;
}

/**
 * get url in route
 *
 * @return string
 */
function coreRouteGetCheckedUrlInRoutes() : string
{
    return $GLOBALS['checkedUrlInRoutes'];
}

function coreRouteHandleGroupStack()
{
    $groupStack = coreRouteGetGroupStack();

    $namespace = array_column($groupStack, 'namespace');
    $prefix = array_column($groupStack, 'prefix');
    $arrMiddleware = array_column($groupStack, 'middleware');

    $middleware = [];
    foreach ($arrMiddleware as $value) {
        if(is_array($value)) {
            foreach ($value as $v) {
                $middleware[] = $v;
            }
        } else {
            $middleware[] = $value;
        }
    }

    coreRouteSetNamespace(implode('/', $namespace));
    coreRouteSetPrefix(implode('/', $prefix));
    coreRouteSetMiddleware($middleware);
}

/**
 * @param $controller "ExampleController@index"
 * @return mixed
 */
function coreRouteDispatch($controller)
{
    $controllerAndMethod = coreRouteHandleController($controller);

    require_once '../' . coreRouteGetNamespace() . '/' . $controllerAndMethod['controller'] . '.php';

    $method = $controllerAndMethod['method'];

    echo $$method(...array_values(coreRouteGetParamsInUrl()));
}
