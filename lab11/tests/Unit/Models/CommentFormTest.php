<?php

namespace tests\Unit\models;

use app\models\CommentForm;
use Codeception\Test\Unit;

class CommentFormTest extends Unit
{
    public function testValidCommentPassesValidation()
    {
        $model = new CommentForm([
            'author' => 'Марія',
            'rating' => 5,
            'comment' => 'Чудова книга, дуже зрозумілі приклади рефакторингу коду!',
        ]);

        $this->assertTrue($model->validate());
    }

    public function testEmptyFormFailsValidation()
    {
        $model = new CommentForm();

        $this->assertFalse($model->validate());
        $this->assertArrayHasKey('author', $model->errors);
        $this->assertArrayHasKey('rating', $model->errors);
        $this->assertArrayHasKey('comment', $model->errors);
    }

    public function testRatingOutOfRangeFailsValidation()
    {
        $modelHigh = new CommentForm([
            'author' => 'Іван',
            'rating' => 6,
            'comment' => 'Достатньо довгий коректний текст відгуку про книгу.',
        ]);
        $this->assertFalse($modelHigh->validate());
        $this->assertArrayHasKey('rating', $modelHigh->errors);

        $modelLow = new CommentForm([
            'author' => 'Іван',
            'rating' => 0,
            'comment' => 'Достатньо довгий коректний текст відгуку про книгу.',
        ]);
        $this->assertFalse($modelLow->validate());
        $this->assertArrayHasKey('rating', $modelLow->errors);
    }

    public function testShortCommentFailsValidation()
    {
        $model = new CommentForm([
            'author' => 'Олег',
            'rating' => 4,
            'comment' => 'Коротко',
        ]);

        $this->assertFalse($model->validate());
        $this->assertArrayHasKey('comment', $model->errors);
    }
}