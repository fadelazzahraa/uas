<?php
function pdo_connect()
{
    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'root';
    $DATABASE_PASS = '';
    $DATABASE_NAME = 'web_project';
    $DATABASE_PORT = '3368';
    $dsn = 'mysql:dbname=' . $DATABASE_NAME . ';port=' . $DATABASE_PORT . ';host=' . $DATABASE_HOST;
    $user = $DATABASE_USER;
    $password = $DATABASE_PASS;
    try {
        return new PDO($dsn, $user, $password);
    } catch (PDOEXCEPTION $exception) {
        return null;
    }
}
?>