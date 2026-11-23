<?php

$db = require __DIR__ . '/db.php';
$db['dsn'] = 'sqlite:' . dirname(__DIR__) . '/runtime/test_blog.sqlite';

return $db;