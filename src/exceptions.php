<?php

function dd(...$dd)
{
    dump(...$dd);

    die;
}

function dump(...$dump)
{
    array_map(function ($value) {
        echo '<pre>',print_r($value,1),'</pre>';
    }, $dump);
}

function error($e)
{
    dump($e);
    die;
}
