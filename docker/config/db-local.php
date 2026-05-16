<?php
/**
 * Docker-specific database configuration.
 * Overrides common/config/db-local.php via docker-compose volume mount.
 * The hostname "db" matches the MariaDB service name in docker-compose.yml.
 */
return [
    'class'    => 'yii\db\Connection',
    'dsn'      => 'mysql:host=db;dbname=gssamru_goldmember',
    'username' => 'gssamru_autoimport',
    'password' => 'bGtVSaf2rv6hLWN',
    'charset'  => 'utf8',
];

