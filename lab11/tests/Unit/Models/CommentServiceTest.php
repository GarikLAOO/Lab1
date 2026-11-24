<?php

namespace tests\Unit\models;

use app\models\CommentForm;
use app\models\CommentService;
use Codeception\Test\Unit;
use yii\helpers\Html;

class CommentServiceTest extends Unit
{
    private string $tempFile;
    private CommentService $service;

    protected function _before()
    {
        $this->tempFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'yii2_test_comments_' . uniqid() . '.json';
        $this->service = new CommentService($this->tempFile);
    }

    protected function _after()
    {
        if (is_file($this->tempFile)) {
            @unlink($this->tempFile);
        }
    }

    public function testRatingCategories()
    {
        $this->assertEquals('низька', $this->service->getRatingCategory(1));
        $this->assertEquals('низька', $this->service->getRatingCategory(2));
        $this->assertEquals('середня', $this->service->getRatingCategory(3));
        $this->assertEquals('висока', $this->service->getRatingCategory(4));
        $this->assertEquals('висока', $this->service->getRatingCategory(5));
    }

    public function testModerationDetectsBannedWordsCaseInsensitive()
    {
        $flagged = $this->service->moderateText('Це суцільний СПАМ і реклама!');
        $this->assertEquals(CommentService::STATUS_PENDING, $flagged['status']);

        $clean = $this->service->moderateText('Дуже корисний огляд книги, дякую автору!');
        $this->assertEquals(CommentService::STATUS_PASSED, $clean['status']);
    }

    public function testSaveReadUkrainianCharactersAndAverageRating()
    {
        $form1 = new CommentForm([
            'author' => 'Оксана',
            'rating' => 5,
            'comment' => 'Прекрасна українська рецензія з літерами ї, є, ґ.',
        ]);
        $res1 = $this->service->processAndSave($form1, 'book-post-7');
        $this->assertTrue($res1['saved']);
        $this->assertEquals(5.0, $res1['averageRating']);

        $form2 = new CommentForm([
            'author' => 'Тарас',
            'rating' => 3,
            'comment' => 'Непогана книга, але деякі розділи затягнуті.',
        ]);
        $res2 = $this->service->processAndSave($form2, 'book-post-7');
        $this->assertTrue($res2['saved']);
        $this->assertEquals(4.0, $res2['averageRating']);

        $rawJson = file_get_contents($this->tempFile);
        $this->assertStringContainsString('Оксана', $rawJson);
        $this->assertStringContainsString('ї, є, ґ', $rawJson);

        $savedComments = $this->service->readComments();
        $this->assertCount(2, $savedComments);
    }

    public function testMissingAndCorruptedJsonHandledGracefully()
    {
        $this->assertSame([], $this->service->readComments());

        file_put_contents($this->tempFile, '{broken_json:::');
        $this->assertSame([], $this->service->readComments());
    }

    public function testHtmlEscapingSafety()
    {
        $xss = '<script>alert("XSS")</script>';
        $encoded = Html::encode($xss);

        $this->assertStringNotContainsString('<script>', $encoded);
        $this->assertStringContainsString('&lt;script&gt;', $encoded);
    }
}