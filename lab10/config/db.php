<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'sqlite:' . dirname(__DIR__) . '/data/blog.sqlite',
    'charset' => 'utf8',
    'on afterOpen' => function ($event) {
        $event->sender->createCommand('PRAGMA foreign_keys = ON')->execute();
    },
];
