<?php

namespace app\models;

use yii\base\Model;

class CommentForm extends Model
{
    public $author;
    public $rating;
    public $comment;

    public function rules()
    {
        return [
            [['author', 'rating', 'comment'], 'required', 'message' => 'Це поле є обов’язковим для заповнення.'],
            [['author', 'comment'], 'trim'],
            [
                'author',
                'string',
                'min' => 2,
                'max' => 50,
                'tooShort' => 'Ім’я автора має містити щонайменше 2 символи.',
                'tooLong' => 'Ім’я автора не може перевищувати 50 символів.',
            ],
            [
                'rating',
                'integer',
                'min' => 1,
                'max' => 5,
                'message' => 'Оцінка має бути цілим числом.',
                'tooSmall' => 'Оцінка не може бути меншою за 1 зірку.',
                'tooBig' => 'Оцінка не може бути більшою за 5 зірок.',
            ],
            [
                'comment',
                'string',
                'min' => 10,
                'max' => 1000,
                'tooShort' => 'Текст відгуку має містити щонайменше 10 символів.',
                'tooLong' => 'Текст відгуку не може перевищувати 1000 символів.',
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'author' => 'Ім’я автора відгуку',
            'rating' => 'Оцінка допису (1–5 зірок)',
            'comment' => 'Текст відгуку',
        ];
    }
}