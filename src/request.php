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
 * @return mixed example "/hphp/public/"
 */
function coreRequestRequestURI()
{
    return $_SERVER['REQUEST_URI'];
}

function coreRequestFullUrl()
{
    return coreRequestServerName() . coreRequestRequestURI();
}

function coreRequestQueryURL()
{
    return substr(coreRequestFullUrl(), strlen(coreRequestAppURL()));
}

function coreRequestParams()
{
    return $_REQUEST;
}

