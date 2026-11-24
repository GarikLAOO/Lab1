<?php

namespace app\models;

use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    public $email;
    public $password;
    public $rememberMe = true;

    private ?User $_user = null;
    private bool $_userLoaded = false;

    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'filter', 'filter' => fn($value) => mb_strtolower((string) $value)],
            [['email', 'password'], 'required', 'message' => 'Поле «{attribute}» є обов’язковим.'],
            ['email', 'email', 'message' => 'Введіть коректну адресу електронної пошти.'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'Електронна пошта (Email)',
            'password' => 'Пароль',
            'rememberMe' => 'Запам’ятати мене',
        ];
    }

    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword((string) $this->password)) {
                $this->addError($attribute, 'Неправильний email або пароль.');
            }
        }
    }

    public function login(): bool
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }

        return false;
    }

    public function getUser(): ?User
    {
        if (!$this->_userLoaded) {
            $this->_user = User::findByEmail((string) $this->email);
            $this->_userLoaded = true;
        }

        return $this->_user;
    }
}