<?php

use yii\helpers\Html;
use app\models\CommentService;

$this->title = 'Результат надсилання відгуку';
$starsFilled = str_repeat('★', (int) $record['rating']);
$starsEmpty = str_repeat('☆', 5 - (int) $record['rating']);
$isPending = $record['moderation_status'] === CommentService::STATUS_PENDING;
?>

<div class="alert alert-success">
    <strong>Відгук успішно збережено!</strong> Дякуємо за вашу оцінку допису «<?= Html::encode($post['title']) ?>».
</div>

<div class="card mb-4">
    <div class="card-header">
        <strong>Ваш відгук</strong> — <?= Html::encode($record['author']) ?>
    </div>
    <div class="card-body">
        <p class="mb-2">
            <strong>Оцінка:</strong>
            <span style="color: #f5a623; font-size: 1.4rem;"><?= $starsFilled . $starsEmpty ?></span>
            (<?= Html::encode((string) $record['rating']) ?> / 5)
        </p>

        <p class="mb-2">
            <strong>Категорія оцінки:</strong>
            <span class="badge bg-secondary"><?= Html::encode($ratingCategory) ?></span>
        </p>

        <p class="mb-2">
            <strong>Середня оцінка допису:</strong>
            <?= Html::encode(number_format($averageRating, 2, '.', '')) ?> / 5
        </p>

        <p class="mb-3">
            <strong>Текст відгуку:</strong><br>
            <?= nl2br(Html::encode($record['comment'])) ?>
        </p>

        <div class="alert <?= $isPending ? 'alert-warning' : 'alert-info' ?> mb-3">
            <strong>Статус попередньої модерації (<?= Html::encode($record['moderation_status']) ?>):</strong>
            <?= Html::encode($moderationMessage) ?>
            <br>
            <small class="text-muted">
                Примітка: автоматизована перевірка за списком слів є спрощеною і не означає остаточного схвалення до публікації.
            </small>
        </div>

        <p class="text-muted mb-0">
            <strong>Дата й час створення:</strong> <?= Html::encode($record['created_at']) ?>
        </p>
    </div>
</div>

<?= Html::a('Залишити ще один відгук', ['site/comment'], ['class' => 'btn btn-primary']) ?>