<?php

namespace app\models;

use yii\db\ActiveRecord;

class Review extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%reviews}}';
    }

    /**
     * Зв’язок: відгук належить конкретній публікації.
     */
    public function getPost()
    {
        return $this->hasOne(Post::class, ['id' => 'post_id']);
    }

    public function rules()
    {
        return [
            [['author_name', 'author_email', 'comment'], 'trim'],
            [['post_id', 'author_name', 'author_email', 'rating', 'comment'], 'required', 'message' => 'Це поле є обов’язковим.'],
            ['post_id', 'integer'],
            ['post_id', 'exist', 'targetClass' => Post::class, 'targetAttribute' => ['post_id' => 'id']],
            ['author_name', 'string', 'min' => 2, 'max' => 100],
            ['author_email', 'email', 'message' => 'Введіть коректну адресу електронної пошти.'],
            ['rating', 'integer', 'min' => 1, 'max' => 5],
            ['comment', 'string', 'min' => 5, 'max' => 1000],
            ['created_at', 'safe'],
        ];
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if (empty($this->created_at)) {
            $this->created_at = date('Y-m-d H:i:s');
        }

        return true;
    }

    public function attributeLabels()
    {
        return [
            'author_name' => 'Ваше ім’я',
            'author_email' => 'Ваш Email',
            'rating' => 'Оцінка (1–5)',
            'comment' => 'Текст відгуку',
            'created_at' => 'Дата відгуку',
        ];
    }
}