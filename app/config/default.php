<?php

return [
  'app_name'  => 'Assegai',
  'version'   => '0.0.1',
  'default_password_hash_algo' => PASSWORD_DEFAULT,
  'databases' => [
    'mysql' => [
      'db_name' => [
        'host'      => 'localhost',
        'user'      => '',
        'password'  => '',
        'name'      => 'db_name',
        'port'      => 3306,
      ]
    ],
    'pgsql' => [
      'db_name' => [
        'host'      => 'localhost',
        'user'      => '',
        'password'  => '',
        'name'      => 'db_name',
        'port'      => 5432,
      ]
    ],
    'sqlite' => [
      'db_name' => [
        'path' => '.data/db_name.sq3'
      ]
    ],
    'mongodb' => [],
  ],
  'request' => [
    'DEFAULT_LIMIT' => 100,
    'DEFAULT_SKIP'  => 0,
  ],
  'authentication' => [
    'default_strategy' => 'local',
    'jwt' => [
      'audience'                => 'https://yourdomain.com',
      'issuer'                  => 'assegai',
      'lifespan'                => '1 hour',
      'entityName'              => 'user',
      'entityClassName'         => Assegai\Modules\Users\UserEntity::class,
      'entityIdFieldname'       => 'email',
      'entityPasswordFieldname' => 'password',
    ]
  ]
];
