<?php

return [
  'databases' => [
    'mysql' => [
      'host' => 'localhost',
      'user' => 'navigator',
      'password' => '2m80tina',
      'name' => 'navigator_local_db',
      'port' => 3306,
    ],
    'mariadb' => [
      'host' => 'localhost',
      'user' => 'navigator',
      'password' => '2m80tina',
      'name' => 'navigator_local_db',
      'port' => 3306,
    ],
    'postgresql' => [],
    'sqlite' => [],
    'mongodb' => [],
  ],
  'request' => [
    'DEFAULT_LIMIT' => 100,
    'DEFAULT_SKIP' => 0,
  ]
];

?>