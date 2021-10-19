<?php

return [
  'app_name' => 'Assegai',
  'version' => '0.0.1',
  'default_password_hash_algo' => PASSWORD_DEFAULT,
  'databases' => [
    'mysql' => [
      'navigator_local_db' => [
        'host' => 'localhost',
        'user' => 'navigator',
        'password' => '2m80tina',
        'name' => 'navigator_local_db',
        'port' => 3306,
      ]
    ],
    'mariadb' => [
      'navigator_local_db' => [
        'host' => 'localhost',
        'user' => 'navigator',
        'password' => '2m80tina',
        'name' => 'navigator_local_db',
        'port' => 3306,
      ]
    ],
    'pgsql' => [
      'navigator_local_db' => [
        'host' => 'localhost',
        'user' => 'navigator',
        'password' => '2m80tina',
        'name' => 'navigator_local_db',
        'port' => 5432,
      ]
    ],
    'sqlite' => [
      'assegai_test' => [
        'path' => '.data/assegai_test.sq3'
      ]
    ],
    'mongodb' => [],
  ],
  'request' => [
    'DEFAULT_LIMIT' => 100,
    'DEFAULT_SKIP' => 0,
  ]
];

?>