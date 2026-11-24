<?php

namespace app\models;

use yii\base\Model;

class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $password_repeat;

    public function rules()
    {
        return [
            [['username', 'email'], 'trim'],
            ['email', 'filter', 'filter' => fn($value) => mb_strtolower((string) $value)],

            [
                ['username', 'email', 'password', 'password_repeat'],
                'required',
                'message' => 'Поле «{attribute}» є обов’язковим для заповнення.',
            ],

            [
                'username',
                'string',
                'min' => 3,
                'max' => 30,
                'tooShort' => 'Логін має містити щонайменше 3 символи.',
                'tooLong' => 'Логін не може перевищувати 30 символів.',
            ],
            [
                'username',
                'match',
                'pattern' => '/^[a-zA-Z0-9_]+$/',
                'message' => 'Логін може містити лише латинські літери, цифри та знак підкреслення (_).',
            ],
            [
                'username',
                'unique',
                'targetClass' => User::class,
                'targetAttribute' => 'username',
                'message' => 'Користувач із таким логіном уже зареєстрований.',
            ],

            ['email', 'email', 'message' => 'Введіть коректну адресу електронної пошти.'],
            ['email', 'string', 'max' => 150],
            [
                'email',
                'unique',
                'targetClass' => User::class,
                'targetAttribute' => 'email',
                'message' => 'Ця адреса електронної пошти вже використовується.',
            ],

            [
                'password',
                'string',
                'min' => 6,
                'max' => 64,
                'tooShort' => 'Пароль має містити щонайменше 6 символів.',
                'tooLong' => 'Пароль не може перевищувати 64 символи.',
            ],
            [
                'password',
                'match',
                'pattern' => '/^(?=.*[A-Za-z])(?=.*\d)\S+$/',
                'message' => 'Пароль має містити щонайменше одну латинську літеру, одну цифру та не містити пробілів.',
            ],
            [
                'password_repeat',
                'compare',
                'compareAttribute' => 'password',
                'message' => 'Паролі не збігаються.',
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Логін читача',
            'email' => 'Електронна пошта (Email)',
            'password' => 'Пароль',
            'password_repeat' => 'Підтвердження пароля',
        ];
    }


    public function signup(): ?User
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->created_at = date('Y-m-d H:i:s');

        return $user->save(false) ? $user : null;
    }
}