<?php

namespace app\models;

use yii\db\ActiveRecord;

class Post extends ActiveRecord
{
    public const MIN_TITLE_LENGTH = 5;
    public const MAX_TITLE_LENGTH = 150;
    public const MIN_CONTENT_WORDS = 10;
    public const MAX_CONTENT_LENGTH = 10000;

    public static function tableName()
    {
        return '{{%posts}}';
    }

    /**
     * Зв’язок: одна публікація має багато відгуків.
     */
    public function getReviews()
    {
        return $this->hasMany(Review::class, ['post_id' => 'id']);
    }


    public static function countWords(?string $text): int
    {
        if ($text === null || trim($text) === '') {
            return 0;
        }
        preg_match_all('/[\p{L}\p{N}]+(?:[\'’\-][\p{L}\p{N}]+)*/u', $text, $matches);
        return count($matches[0]);
    }

    public function rules()
    {
        return [
            [
                ['title'],
                'filter',
                'filter' => function ($value) {
                    $value = trim((string) $value);
                    return preg_replace('/\s+/u', ' ', $value);
                },
            ],
            [
                ['content'],
                'filter',
                'filter' => function ($value) {
                    $value = str_replace(["\r\n", "\r"], "\n", (string) $value);
                    return trim($value);
                },
            ],
            [['title', 'content'], 'required', 'message' => 'Поле «{attribute}» є обов’язковим і не може бути порожнім.'],
            [
                'title',
                'string',
                'encoding' => 'UTF-8',
                'min' => self::MIN_TITLE_LENGTH,
                'max' => self::MAX_TITLE_LENGTH,
                'tooShort' => 'Заголовок має містити щонайменше ' . self::MIN_TITLE_LENGTH . ' символів.',
                'tooLong' => 'Заголовок не може перевищувати ' . self::MAX_TITLE_LENGTH . ' символів.',
            ],
            [
                'content',
                'string',
                'encoding' => 'UTF-8',
                'max' => self::MAX_CONTENT_LENGTH,
                'tooLong' => 'Текст публікації не може перевищувати ' . self::MAX_CONTENT_LENGTH . ' символів.',
            ],
            ['content', 'validateMinWords'],
            ['published', 'boolean'],
            ['published', 'default', 'value' => 1],
            ['published_at', 'safe'],
        ];
    }

    public function validateMinWords($attribute)
    {
        $wordsCount = self::countWords($this->$attribute);
        if ($wordsCount < self::MIN_CONTENT_WORDS) {
            $this->addError(
                $attribute,
                "Текст публікації містить лише {$wordsCount} сл. Доповніть текст щонайменше до " . self::MIN_CONTENT_WORDS . ' слів.'
            );
        }
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if ($this->published && empty($this->published_at)) {
            $this->published_at = date('Y-m-d H:i:s');
        } elseif (!$this->published) {
            $this->published_at = null;
        }

        return true;
    }

    public function attributeLabels()
    {
        return [
            'title' => 'Заголовок публікації',
            'content' => 'Текст публікації',
            'published' => 'Опублікувати одразу',
            'published_at' => 'Дата публікації',
        ];
    }
}