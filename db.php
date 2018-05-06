<?php
error_reporting(E_ERROR | E_PARSE);
function db($master = false)
{
    $slaveHosts = [
        'mysql-slave1',
        'mysql-slave2'
    ];
    $host       = $master ? 'mysql-master' : $slaveHosts[rand(0, 1)];
    $db         = new mysqli($host, 'root', 'root', 'demo');
    return $db;
}