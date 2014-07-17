<?php
return array(

    'doctrine' => array(

        // Connections
        'connection' => array(
            'orm_default' => array(
                'driverClass' =>'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host'     => 'localhost',
                    'port'     => '3306',
                    'user'     => 'nakade',
                    'password' => 'NidBLT2012',
                    'dbname'   => 'nakade',
                    'driverOptions' => array(
                        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
                    ),
                )
            ),
        ),
    )

);