<?php

namespace app\models;

use yii\base\Model;

class EntryForm extends Model
{
    public $name;
    public $email;
    public $booksPerYear;

    public function rules()
    {
        return [
            [['name', 'email', 'booksPerYear'], 'required', 'message' => 'Це поле є обов’язковим.'],
            ['name', 'trim'],
            ['name', 'string', 'min' => 2, 'max' => 60],
            ['email', 'email', 'message' => 'Введіть коректну адресу електронної пошти.'],
            [
                'booksPerYear',
                'integer',
                'min' => 0,
                'max' => 500,
                'message' => 'Вкажіть ціле число.',
                'tooSmall' => 'Кількість книг не може бути меншою за 0.',
                'tooBig' => 'Вкажіть реалістичне число (до 500).',
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Ім’я читача',
            'email' => 'Адреса електронної пошти (Email)',
            'booksPerYear' => 'Кількість прочитаних книг за рік',
        ];
    }

    /**
     * Обчислення середньої кількості книг на місяць.
     */
    public function getBooksPerMonth(): float
    {
        return round(((int) $this->booksPerYear) / 12, 2);
    }

    /**
     * Класифікація активності читача.
     */
    public function getReaderCategory(): string
    {
        $count = (int) $this->booksPerYear;
        if ($count < 6) {
            return 'Читач-початківець';
        }
        if ($count <= 24) {
            return 'Активний читач';
        }
        return 'Затятий книголюб';
    }
}