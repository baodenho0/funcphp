<?php

$table = 'model';
$from = 'model';

$qWhere = ' WHERE ';

$connection = function ()
{
    $mysql = $GLOBALS['appConfig']['database']['mysql'];

    $conn = mysqli_connect(
        $mysql['host'],
        $mysql['username'],
        $mysql['password'],
        $mysql['database']
    );


    if(mysqli_connect_errno()) {
        error("Failed to connect to MySQL: " . mysqli_connect_error());
    }

    return $conn;
};

$execute = function ($query) use ($connection)
{
    $results = [];
    $conn = $connection();

    $exe = mysqli_query($conn, $query);

    if(is_object($exe)) {
        if(mysqli_num_rows($exe) > 0) {
            while($row = mysqli_fetch_assoc($exe)) {
                $results[] = $row;
            }
        }
    } elseif($exe == 1) {
        $results = true;
    } else {
        $results = false;
    }

    mysqli_close($conn);

    return $results;
};

$get = function () use ($from, &$qWhere, $execute)
{
    $qWhere = substr(trim($qWhere), 0, -3);
    $query = "SELECT * FROM `$from` $qWhere";

    return $execute($query);
};

$insert = function ($params) use ($from, $execute)
{
    $qKey = NULL;
    $qValue = '';
    foreach ($params as $key => $value) {
        $qKey .= " `$key`,";
        $qValue .= " '$value',";
    }
    $qKey = substr(trim($qKey), 0, -1);
    $qValue = substr(trim($qValue), 0, -1);

    $query = "INSERT INTO `$from` ($qKey) VALUES ($qValue)";

    return $execute($query);
};

$update = function ($params) use ($from, &$qWhere, $execute)
{
    $qSet = 'SET';
    foreach ($params as $key => $value) {
        $qSet .= " `$key` = '$value' ,";
    }
    $qSet = substr(trim($qSet), 0, -1);
    $qWhere = substr(trim($qWhere), 0, -3);
    $query = "UPDATE `$from` $qSet $qWhere";

    return $execute($query);
};

$delete = function () use ($from, &$qWhere, $execute)
{
    $qWhere = substr(trim($qWhere), 0, -3);
    $query = "DELETE FROM `$from` $qWhere";

    return $execute($query);
};


$where = function ($column, $operator, $value) use (&$qWhere, &$where, $get, $update, $delete)
{
    $qWhere .= "`$column`  $operator  '$value' AND ";

    return [
        'where' => $where,
        'get' => $get,
        'update' => $update,
        'delete' => $delete,
    ];
};

$setTable = function ($table) use ($from)
{
      $from = $table;
};

$getTable = function () use ($from)
{
    return $from;
};

return export('../vendor/hphp/framework/src/model.php', compact(
    'table',
    'get',
    'insert',
    'update',
    'delete',
    'where'
));
