<?php return [
    'db' =>
        [
            'host' => 'mysql',
            'port' => '3306',
            'username' => getenv('DB_USERNAME'),
            'password' => getenv('DB_PASSWORD'),
            'dbname' => 'shopware',
        ],
];