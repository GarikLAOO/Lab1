<?php

namespace tests\Unit\models;

use Yii;
use Codeception\Test\Unit;
use yii\helpers\Html;
use app\models\Post;
use app\models\Review;
use app\models\PostStatsService;

class PostDbTest extends Unit
{
    protected function _before()
    {
        $db = Yii::$app->db;
        $db->createCommand('PRAGMA foreign_keys = ON')->execute();

        $db->createCommand('CREATE TABLE IF NOT EXISTS posts (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title VARCHAR(150) NOT NULL,
            content TEXT NOT NULL,
            published INTEGER NOT NULL DEFAULT 1,
            published_at DATETIME NULL
        )')->execute();

        $db->createCommand('CREATE TABLE IF NOT EXISTS reviews (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            post_id INTEGER NOT NULL,
            author_name VARCHAR(100) NOT NULL,
            author_email VARCHAR(150) NOT NULL,
            rating INTEGER NOT NULL,
            comment TEXT NOT NULL,
            created_at DATETIME NOT NULL,
            FOREIGN KEY (post_id) REFERENCES posts (id) ON DELETE CASCADE
        )')->execute();

        $db->createCommand()->delete('reviews')->execute();
        $db->createCommand()->delete('posts')->execute();
    }

    /**
     * Сценарій 1: Очищення пробілів, збереження абзаців, збереження та отримання з БД, безпека HTML.
     */
    public function testPostCleaningSavingAndHtmlSafety()
    {
        $post = new Post([
            'title' => "   Огляд   книги\t\t«Чистий   код»   ",
            'content' => "   Перший абзац описує <b>чистий код</b> та важливість правильних назв змінних.\r\n\r\nДругий абзац містить практичні поради щодо рефакторингу функцій.   ",
            'published' => 1,
        ]);

        $this->assertTrue($post->save(), 'Коректна публікація має зберегтися в БД');

        $loaded = Post::findOne($post->id);
        $this->assertNotNull($loaded);
        $this->assertEquals('Огляд книги «Чистий код»', $loaded->title);
        $this->assertStringContainsString("\n\n", $loaded->content);
        $this->assertStringContainsString('<b>чистий код</b>', $loaded->content);

        // Перевірка безпечного виведення у представленні
        $encoded = Html::encode($loaded->content);
        $this->assertStringNotContainsString('<b>', $encoded);
        $this->assertStringContainsString('&lt;b&gt;чистий код&lt;/b&gt;', $encoded);
    }

    /**
     * Сценарій 2: Відхилення невалідних даних (порожні після trim, короткий заголовок, < 10 слів).
     */
    public function testPostValidationRejectsInvalidData()
    {
        $emptyPost = new Post([
            'title' => '     ',
            'content' => "   \n\t   ",
        ]);
        $this->assertFalse($emptyPost->save());
        $this->assertArrayHasKey('title', $emptyPost->errors);
        $this->assertArrayHasKey('content', $emptyPost->errors);

        $shortPost = new Post([
            'title' => 'Код',
            'content' => 'Лише п’ять слів у тексті.',
        ]);
        $this->assertFalse($shortPost->save());
        $this->assertArrayHasKey('title', $shortPost->errors);
        $this->assertArrayHasKey('content', $shortPost->errors);
        $this->assertEquals(0, Post::find()->count());
    }

    /**
     * Сценарій 3: SQL-статистика відгуків (COUNT, AVG, MIN, MAX, розподіл 1–5, рейтинг та пост без відгуків).
     */
    public function testReviewsStatisticsAndRanking()
    {
        $postA = new Post([
            'title' => 'Книга про алгоритми та структури даних',
            'content' => 'Детальний огляд фундаментальної книги з алгоритмів для студентів технічних спеціальностей університету.',
            'published' => 1,
        ]);
        $this->assertTrue($postA->save());

        $postB = new Post([
            'title' => 'Книга про бази даних і SQL',
            'content' => 'Практичний посібник із проєктування реляційних баз даних, індексів, транзакцій та оптимізації запитів.',
            'published' => 1,
        ]);
        $this->assertTrue($postB->save());

        $postNoReviews = new Post([
            'title' => 'Нова книга без жодного відгуку',
            'content' => 'Ця публікація щойно з’явилася на сайті, тому читачі ще не встигли її оцінити.',
            'published' => 1,
        ]);
        $this->assertTrue($postNoReviews->save());

        // Відгуки для postA: оцінки 5, 5, 2 -> avg = 4.00, min = 2, max = 5
        foreach ([5, 5, 2] as $i => $rating) {
            $rev = new Review([
                'post_id' => $postA->id,
                'author_name' => "Читач {$i}",
                'author_email' => "user{$i}@example.com",
                'rating' => $rating,
                'comment' => 'Хороший змістовний відгук про книгу.',
            ]);
            $this->assertTrue($rev->save());
        }

        // Відгук для postB: оцінка 5 -> avg = 5.00 (має бути вище у рейтингу!)
        $revB = new Review([
            'post_id' => $postB->id,
            'author_name' => 'Олена',
            'author_email' => 'olena@example.com',
            'rating' => 5,
            'comment' => 'Найкраща книга про SQL, яку я читала.',
        ]);
        $this->assertTrue($revB->save());

        $service = new PostStatsService();
        $stats = $service->getFullStatistics();

        $this->assertCount(3, $stats);

        // 1-ше місце: postB (середня 5.00)
        $this->assertEquals($postB->id, $stats[0]['id']);
        $this->assertEquals(1, $stats[0]['rank']);
        $this->assertEquals(1, $stats[0]['reviews_count']);
        $this->assertEquals(5.0, $stats[0]['avg_rating']);

        // 2-ге місце: postA (середня 4.00, min 2, max 5, розподіл: дві 5-ки та одна 2-ка)
        $this->assertEquals($postA->id, $stats[1]['id']);
        $this->assertEquals(2, $stats[1]['rank']);
        $this->assertEquals(3, $stats[1]['reviews_count']);
        $this->assertEquals(4.0, $stats[1]['avg_rating']);
        $this->assertEquals(2, $stats[1]['min_rating']);
        $this->assertEquals(5, $stats[1]['max_rating']);
        $this->assertEquals([1 => 0, 2 => 1, 3 => 0, 4 => 0, 5 => 2], $stats[1]['distribution']);

        // 3-тя позиція: postNoReviews (без відгуків)
        $this->assertEquals($postNoReviews->id, $stats[2]['id']);
        $this->assertNull($stats[2]['rank']);
        $this->assertEquals(0, $stats[2]['reviews_count']);
        $this->assertNull($stats[2]['avg_rating']);
    }
}