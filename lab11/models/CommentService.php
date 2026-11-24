<?php

namespace app\models;

use Yii;

class CommentService
{
    public const STATUS_PENDING = 'pending_review';
    public const STATUS_PASSED = 'preliminary_check_passed';

    private string $storagePath;

    /** @var string[] Тестовий список заборонених слів/словосполучень */
    private array $bannedWords = [
        'спам',
        'шахрайство',
        'лохотрон',
        'дурень',
        'ідіот',
        'купити дешево',
        'казино',
        'spam',
        'scam',
    ];

    public function __construct(?string $storagePath = null)
    {
        $this->storagePath = $storagePath ?? Yii::getAlias('@app/data/comments.json');
    }

    public function loadPost(?string $postPath = null): array
    {
        $path = $postPath ?? Yii::getAlias('@app/data/post.json');
        $fallback = [
            'id' => 'book-post-7',
            'title' => 'Огляд книги',
            'book_author' => 'Невідомий автор',
            'genre' => 'Література',
            'published_at' => date('Y-m-d'),
            'content' => 'Вміст допису тимчасово недоступний.',
        ];

        if (!is_file($path)) {
            return $fallback;
        }

        $raw = @file_get_contents($path);
        if ($raw === false) {
            return $fallback;
        }

        $decoded = json_decode($raw, true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
            return $fallback;
        }

        return array_merge($fallback, $decoded);
    }

    public function normalizeRating($rating): int
    {
        return (int) $rating;
    }

    public function getRatingCategory(int $rating): string
    {
        if ($rating <= 2) {
            return 'низька';
        }
        if ($rating === 3) {
            return 'середня';
        }
        return 'висока';
    }

    public function calculateAverageRating(array $existingComments, int $newRating, string $postId): float
    {
        $ratings = [];
        foreach ($existingComments as $item) {
            if (
                is_array($item)
                && ($item['post_id'] ?? null) === $postId
                && isset($item['rating'])
                && is_numeric($item['rating'])
            ) {
                $ratings[] = (int) $item['rating'];
            }
        }
        $ratings[] = $newRating;

        return round(array_sum($ratings) / count($ratings), 2);
    }

    public function moderateText(string $text): array
    {
        $matched = [];
        foreach ($this->bannedWords as $word) {
            if (mb_stripos($text, $word, 0, 'UTF-8') !== false) {
                $matched[] = $word;
            }
        }

        if (!empty($matched)) {
            return [
                'status' => self::STATUS_PENDING,
                'message' => 'Відгук потребує додаткової перевірки модератором (виявлено потенційно небажані слова).',
                'matched' => $matched,
            ];
        }

        return [
            'status' => self::STATUS_PASSED,
            'message' => 'Відгук пройшов попередню автоматизовану перевірку.',
            'matched' => [],
        ];
    }

    public function readComments(): array
    {
        if (!is_file($this->storagePath)) {
            return [];
        }

        $fp = @fopen($this->storagePath, 'r');
        if ($fp === false) {
            return [];
        }

        $content = '';
        if (flock($fp, LOCK_SH)) {
            $content = stream_get_contents($fp);
            flock($fp, LOCK_UN);
        }
        fclose($fp);

        if ($content === false || trim($content) === '') {
            return [];
        }

        $decoded = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
            return [];
        }

        return $decoded;
    }

    public function appendComment(array $newComment): bool
    {
        $dir = dirname($this->storagePath);
        if (!is_dir($dir) && !@mkdir($dir, 0775, true)) {
            return false;
        }

        $fp = @fopen($this->storagePath, 'c+');
        if ($fp === false) {
            return false;
        }

        if (!flock($fp, LOCK_EX)) {
            fclose($fp);
            return false;
        }

        $content = stream_get_contents($fp);
        $comments = [];
        if ($content !== false && trim($content) !== '') {
            $decoded = json_decode($content, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $comments = $decoded;
            }
        }

        $comments[] = $newComment;
        $json = json_encode($comments, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if ($json === false) {
            flock($fp, LOCK_UN);
            fclose($fp);
            return false;
        }

        ftruncate($fp, 0);
        rewind($fp);
        $written = fwrite($fp, $json);
        fflush($fp);
        flock($fp, LOCK_UN);
        fclose($fp);

        return $written !== false;
    }

    public function processAndSave(CommentForm $form, string $postId): array
    {
        $rating = $this->normalizeRating($form->rating);
        $ratingCategory = $this->getRatingCategory($rating);
        $moderation = $this->moderateText($form->comment);

        $existingComments = $this->readComments();
        $averageRating = $this->calculateAverageRating($existingComments, $rating, $postId);

        $record = [
            'id' => uniqid('cmt_', true),
            'post_id' => $postId,
            'author' => $form->author,
            'comment' => $form->comment,
            'rating' => $rating,
            'moderation_status' => $moderation['status'],
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $saved = $this->appendComment($record);

        return [
            'saved' => $saved,
            'record' => $record,
            'ratingCategory' => $ratingCategory,
            'averageRating' => $averageRating,
            'moderationMessage' => $moderation['message'],
        ];
    }
}