<?php

return array(

    'blog_database_connection' => array(
        'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
        'host'        => 'localhost',
        'port'        => '3306',
        'user'        => 'wordpress',
        'password'    => 'j7F9wMWKLdXEJTbP',
        'dbname'      => 'bbc-blog',
        'driverOptions' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
        ),
    ),

);
