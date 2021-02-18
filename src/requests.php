<?php
function coreRequestsAppURL()
{
    return 'sources.test/functionalprogramming/public/';
}

function coreRequestsServerName()
{
    return $_SERVER['SERVER_NAME'];
}

function coreRequestsRequestURI()
{
    return $_SERVER['REDIRECT_URL'];
}

function coreRequestsFullUrl()
{
    return coreRequestsServerName() . coreRequestsRequestURI();
}

function coreRequestsQueryURL()
{
    return substr(coreRequestsFullUrl(), strlen(coreRequestsAppURL()));
}

function coreRequestsParams()
{
    return $_REQUEST;
}

