<?php

namespace app\models;

use yii\db\Expression;
use yii\db\Query;

class PostStatsService
{
    /**
     * Статистика та рейтинг усіх публікацій за середньою оцінкою на рівні БД.
     */
    public function getFullStatistics(): array
    {
        // 1. Основні агрегати (COUNT, AVG, MIN, MAX) + рейтинг (ORDER BY)
        $rows = (new Query())
            ->select([
                'p.id',
                'p.title',
                'p.published',
                'p.published_at',
                'reviews_count' => 'COUNT(r.id)',
                'avg_rating' => 'AVG(r.rating)',
                'min_rating' => 'MIN(r.rating)',
                'max_rating' => 'MAX(r.rating)',
            ])
            ->from(['p' => Post::tableName()])
            ->leftJoin(['r' => Review::tableName()], 'r.post_id = p.id')
            ->groupBy(['p.id', 'p.title', 'p.published', 'p.published_at'])
            ->orderBy([
                new Expression('CASE WHEN AVG(r.rating) IS NULL THEN 1 ELSE 0 END ASC'),
                new Expression('AVG(r.rating) DESC'),
                new Expression('COUNT(r.id) DESC'),
                'p.title' => SORT_ASC,
            ])
            ->all();

        // 2. Розподіл оцінок (1..5) для кожної публікації через GROUP BY post_id, rating
        $distRows = (new Query())
            ->select([
                'post_id',
                'rating',
                'cnt' => 'COUNT(*)',
            ])
            ->from(Review::tableName())
            ->groupBy(['post_id', 'rating'])
            ->all();

        $distributions = [];
        foreach ($distRows as $d) {
            $postId = (int) $d['post_id'];
            $rating = (int) $d['rating'];
            if (!isset($distributions[$postId])) {
                $distributions[$postId] = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
            }
            if ($rating >= 1 && $rating <= 5) {
                $distributions[$postId][$rating] = (int) $d['cnt'];
            }
        }

        // 3. Об’єднання результатів
        $result = [];
        $rank = 1;
        foreach ($rows as $row) {
            $postId = (int) $row['id'];
            $count = (int) $row['reviews_count'];
            $result[] = [
                'rank' => $count > 0 ? $rank++ : null,
                'id' => $postId,
                'title' => $row['title'],
                'published' => (bool) $row['published'],
                'published_at' => $row['published_at'],
                'reviews_count' => $count,
                'avg_rating' => $count > 0 ? round((float) $row['avg_rating'], 2) : null,
                'min_rating' => $count > 0 ? (int) $row['min_rating'] : null,
                'max_rating' => $count > 0 ? (int) $row['max_rating'] : null,
                'distribution' => $distributions[$postId] ?? [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0],
            ];
        }

        return $result;
    }

    /**
     * Швидка зведена статистика (кількість та середня оцінка) для списку публікацій на post/index.
     */
    public function getSummaryByPostIds(array $postIds): array
    {
        if (empty($postIds)) {
            return [];
        }

        $rows = (new Query())
            ->select([
                'post_id',
                'reviews_count' => 'COUNT(*)',
                'avg_rating' => 'AVG(rating)',
            ])
            ->from(Review::tableName())
            ->where(['post_id' => $postIds])
            ->groupBy('post_id')
            ->all();

        $map = [];
        foreach ($rows as $row) {
            $map[(int) $row['post_id']] = [
                'reviews_count' => (int) $row['reviews_count'],
                'avg_rating' => round((float) $row['avg_rating'], 2),
            ];
        }

        return $map;
    }
}