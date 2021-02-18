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
function coreRoutesGroup(array $arrParams, closure $callback)
{
    if(coreRoutesGetCheckedUrlInRoutes()) {
        return false;
    }

    coreRoutesAddGroupStack($arrParams);
    coreRoutesHandleGroupStack();

    $args = func_get_args();
    call_user_func_array($callback, $args);

    coreRoutesUnsetLastGroupStack();
}

function coreRoutesAddGroupStack($arrParams)
{
    if(!coreRoutesGetCheckedUrlInRoutes()) {
        $GLOBALS['groupStack'][] = $arrParams;
    }
}

function coreRoutesUnsetLastGroupStack()
{
    if(!coreRoutesGetCheckedUrlInRoutes()) {
        array_pop( $GLOBALS['groupStack']);
    }
}

function coreRoutesGetGroupStack()
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
function coreRoutesGet($url, $controller)
{
    if(coreRoutesCheckUrl($url)) {
        coreRoutesDispatch($controller);
    }
}

function coreRoutesArrUrlInRoute($url)
{
    if($prefix = coreRoutesGetPrefix()) {
        $url = $prefix . '/' . $url;
    }

    return explode('/', $url);
}

function coreRoutesGetArrQueryUrl()
{
    return explode('/', coreRequestsQueryURL());
}

function coreRoutesCheckUrl($url)
{
    if(coreRoutesGetCheckedUrlInRoutes()) {
        return false;
    }

    coreRoutesSetDefaultParamsInUrl();

    $queryUrl = coreRoutesGetArrQueryUrl();
    $urlInRoutes = coreRoutesArrUrlInRoute($url);

    if(count($queryUrl) != count($urlInRoutes)) {
        return false;
    }

    foreach ($queryUrl as $key => $value) {
        if(preg_match('/\{(.*?)\}/', $urlInRoutes[$key], $match)) {

            coreRoutesSetParamsInUrl($match[1], $queryUrl[$key]);

            continue;
        }

        if($value != $urlInRoutes[$key]) {
            return false;
        }
    }

    coreRoutesSetCheckedUrlInRoutes(implode('/', $urlInRoutes));

    return true;
}

/**
 * get array params {id}
 *
 * @return array
 */
function coreRoutesGetParamsInUrl() : array
{
    return $GLOBALS['params'];
}

function coreRoutesSetParamsInUrl($key, $value)
{
    $GLOBALS['params'][$key] = $value;
}

function coreRoutesSetDefaultParamsInUrl()
{
    $GLOBALS['params'] = [];
}

function coreRoutesSetNamespace(string $namespace)
{
    $GLOBALS['namespace'] = $namespace;
}

/**
 * get namespace in route group
 *
 * @return string
 */
function coreRoutesGetNamespace() : string
{
    return $GLOBALS['namespace'];
}

function coreRoutesSetPrefix(string $prefix)
{
    $GLOBALS['prefix'] = $prefix;
}

/**
 * get prefix in route group
 *
 * @return string
 */
function coreRoutesGetPrefix() : string
{
    return $GLOBALS['prefix'];
}

function coreRoutesSetMiddleware(array $middleware)
{
    $GLOBALS['middleware'] = $middleware;
}

function coreRoutesGetMiddleware() : array
{
    return $GLOBALS['middleware'];
}

/**
 * @param string $controller "ExampleController@index"
 * @return array
 */
function coreRoutesHandleController(string $controller)
{
    $explode = explode('@', $controller);

    if(!isset($explode[0]) || !isset($explode[1]) || !$explode[0] || !$explode[1]) {
        error('Error: ' . coreRoutesGetCheckedUrlInRoutes() . ' | ' . $controller);
    }

    return [
        'controller' => $explode[0],
        'method' => $explode[1],
    ];
}

function coreRoutesSetCheckedUrlInRoutes(string $url)
{
    $GLOBALS['checkedUrlInRoutes'] = $url;
}

/**
 * get url in route
 *
 * @return string
 */
function coreRoutesGetCheckedUrlInRoutes() : string
{
    return $GLOBALS['checkedUrlInRoutes'];
}

function coreRoutesHandleGroupStack()
{
    $groupStack = coreRoutesGetGroupStack();

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

    coreRoutesSetNamespace(implode('/', $namespace));
    coreRoutesSetPrefix(implode('/', $prefix));
    coreRoutesSetMiddleware($middleware);
}

/**
 * @param $controller "ExampleController@index"
 * @return mixed
 */
function coreRoutesDispatch($controller)
{
    $controllerAndMethod = coreRoutesHandleController($controller);

    require_once '../' . coreRoutesGetNamespace() . '/' . $controllerAndMethod['controller'] . '.php';

    $method = $controllerAndMethod['method'];

    echo $$method(...array_values(coreRoutesGetParamsInUrl()));
}
