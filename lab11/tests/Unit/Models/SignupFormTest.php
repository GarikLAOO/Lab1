<?php

namespace tests\Unit\models;

use Yii;
use Codeception\Test\Unit;
use app\models\SignupForm;
use app\models\User;

class SignupFormTest extends Unit
{
    protected function _before()
    {
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
    }

    public function testSuccessfulSignupSavesUserWithHashedPassword()
    {
        $form = new SignupForm([
            'username' => 'book_reader7',
            'email' => 'Reader7@Example.com',
            'password' => 'Secret123',
            'password_repeat' => 'Secret123',
        ]);

        $user = $form->signup();

        $this->assertNotNull($user);
        $this->assertEquals('book_reader7', $user->username);
        $this->assertEquals('reader7@example.com', $user->email);
        $this->assertNotEquals('Secret123', $user->password_hash);
        $this->assertTrue($user->validatePassword('Secret123'));
        $this->assertEquals(1, User::find()->count());
    }

    public function testEmptyFieldsFailValidation()
    {
        $form = new SignupForm();

        $this->assertNull($form->signup());
        $this->assertArrayHasKey('username', $form->errors);
        $this->assertArrayHasKey('email', $form->errors);
        $this->assertArrayHasKey('password', $form->errors);
        $this->assertArrayHasKey('password_repeat', $form->errors);
        $this->assertEquals(0, User::find()->count());
    }

    public function testInvalidFormatLengthAndPasswordMismatchFailValidation()
    {
        $form = new SignupForm([
            'username' => 'a!',            // занадто короткий + недопустимий символ
            'email' => 'not-an-email',     // некоректний email
            'password' => '12345',         // < 6 символів і без літер
            'password_repeat' => 'other1', // не збігається з password
        ]);

        $this->assertNull($form->signup());
        $this->assertArrayHasKey('username', $form->errors);
        $this->assertArrayHasKey('email', $form->errors);
        $this->assertArrayHasKey('password', $form->errors);
        $this->assertArrayHasKey('password_repeat', $form->errors);
        $this->assertEquals(0, User::find()->count());
    }

    public function testDuplicateUsernameAndEmailAreRejected()
    {
        $first = new SignupForm([
            'username' => 'unique_user',
            'email' => 'unique@example.com',
            'password' => 'Pass1234',
            'password_repeat' => 'Pass1234',
        ]);
        $this->assertNotNull($first->signup());

        $duplicate = new SignupForm([
            'username' => 'unique_user',
            'email' => 'UNIQUE@example.com',
            'password' => 'Pass5678',
            'password_repeat' => 'Pass5678',
        ]);

        $this->assertNull($duplicate->signup());
        $this->assertArrayHasKey('username', $duplicate->errors);
        $this->assertArrayHasKey('email', $duplicate->errors);
        $this->assertEquals(1, User::find()->count());
    }
}