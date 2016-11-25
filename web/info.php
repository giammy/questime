<?php
//phpinfo();

$DB_TYPE = 'pgsql'; //Type of database<br>
$DB_HOST = '127.0.0.1'; //Host name<br>
$DB_USER = 'questimeuser'; //Host Username<br>
$DB_PASS = 'lk9lQpW_yjEWj0qDDXDjLdfgQ9'; //Host Password<br>
$DB_NAME = 'questimedb'; //Database name<br><br>

$dbh = new PDO("$DB_TYPE:host=$DB_HOST; dbname=$DB_NAME;", $DB_USER, $DB_PASS); // PDO Connection
//$dbh = new PDO("$DB_TYPE:host=$DB_HOST; dbname=$DB_NAME;", $DB_USER); // PDO Connection

//var_dump($dbh);
