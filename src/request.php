<?php
/**
 * config app url
 *
 * @return string example sources.test/hphp/public/
 */
function coreRequestAppURL()
{
    return $GLOBALS['appConfig']['appUrl'];
}

/**
 *
 * @return mixed example "sources.test"
 */
function coreRequestServerName()
{
    return $_SERVER['SERVER_NAME'];
}

/**
 * @return mixed example "/hphp/public/create?name=324"
 */
function coreRequestRequestURI()
{
    return $_SERVER['REQUEST_URI'];
}
/**
 * @return mixed example "/hphp/public/create"
 */
function coreRequestRedirectURL()
{
    return $_SERVER['REDIRECT_URL'] ?? '';
}

function coreRequestFullUrl()
{
    return coreRequestServerName() . coreRequestRedirectURL();
}

function coreRequestQueryURL()
{
    return substr(coreRequestFullUrl(), strlen(coreRequestAppURL()));
}
/**
 * get all params in request
 *
 * @return mixed
 */
function coreRequestParams()
{
    return $_REQUEST;
}

function coreRequestCheckMethod($method)
{
    return $method === $_SERVER['REQUEST_METHOD'];
}

