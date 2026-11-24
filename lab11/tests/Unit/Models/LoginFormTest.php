<?php

namespace tests\Unit\models;

use Yii;
use Codeception\Test\Unit;
use app\models\LoginForm;
use app\models\SignupForm;

class LoginFormTest extends Unit
{
    protected function _before()
    {
        Yii::$app->user->enableSession = false;
        Yii::$app->user->setIdentity(null);

        $db = Yii::$app->db;
        $db->createCommand('CREATE TABLE IF NOT EXISTS user (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username VARCHAR(50) NOT NULL UNIQUE,
            email VARCHAR(150) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            auth_key VARCHAR(64) NOT NULL,
            created_at DATETIME NOT NULL
        )')->execute();
        $db->createCommand()->delete('user')->execute();

        $signup = new SignupForm([
            'username' => 'reader_ivan',
            'email' => 'ivan@example.com',
            'password' => 'BookPass7',
            'password_repeat' => 'BookPass7',
        ]);
        $signup->signup();
    }

    public function testLoginWithValidCredentialsAndLogout()
    {
        $login = new LoginForm([
            'email' => 'ivan@example.com',
            'password' => 'BookPass7',
            'rememberMe' => false,
        ]);

        $this->assertTrue($login->login());
        $this->assertFalse(Yii::$app->user->isGuest);
        $this->assertEquals('reader_ivan', Yii::$app->user->identity->username);

        Yii::$app->user->logout();
        $this->assertTrue(Yii::$app->user->isGuest);
    }

    public function testLoginWithWrongPasswordFails()
    {
        $login = new LoginForm([
            'email' => 'ivan@example.com',
            'password' => 'WrongPass9',
        ]);

        $this->assertFalse($login->login());
        $this->assertArrayHasKey('password', $login->errors);
        $this->assertTrue(Yii::$app->user->isGuest);
    }

    public function testLoginWithNonExistentEmailFails()
    {
        $login = new LoginForm([
            'email' => 'ghost@example.com',
            'password' => 'BookPass7',
        ]);

        $this->assertFalse($login->login());
        $this->assertArrayHasKey('password', $login->errors);
        $this->assertTrue(Yii::$app->user->isGuest);
    }
}