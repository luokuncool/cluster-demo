<?php
error_reporting(E_ERROR | E_PARSE);
function db($master = false)
{
    static $connections;
    $slaveHosts = [
        'mysql-slave1',
        'mysql-slave2'
    ];
    $host       = $master ? 'mysql-master' : $slaveHosts[rand(0, 1)];
    if (isset($connections[$host])) {
        return $connections[$host];
    }

    $db = new mysqli($host, 'root', 'root', 'demo');
    $db->query('set names utf8');
    return $db;
}