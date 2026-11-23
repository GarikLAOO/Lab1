<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->title = 'Книжковий блог — Публікації';
$this->registerCssFile('@web/css/post.css');
?>

<div class="post-header-bar">
    <h1>Публікації книжкового блогу</h1>
    <div class="d-flex gap-2">
        <?= Html::a('+ Нова публікація', ['post/create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Статистика та рейтинг', ['post/stats'], ['class' => 'btn btn-outline-primary']) ?>
    </div>
</div>

<?php foreach ($posts as $post): ?>
    <?php
        $summary = $summaryMap[$post->id] ?? ['reviews_count' => 0, 'avg_rating' => null];
    ?>
    <div class="post-card">
        <h2 class="post-title">
            <?= Html::a(Html::encode($post->title), ['post/view', 'id' => $post->id]) ?>
        </h2>

        <div class="post-text">
            <?= nl2br(Html::encode($post->content)) ?>
        </div>

        <div class="post-meta-bar">
            <?php if ($post->published && $post->published_at): ?>
                <span>Дата публікації: <strong><?= Html::encode($post->published_at) ?></strong></span>
            <?php else: ?>
                <span class="badge bg-secondary">Чернетка</span>
            <?php endif; ?>

            <span class="post-badge">
                Відгуків: <?= $summary['reviews_count'] ?>
            </span>

            <span class="post-badge">
                <?php if ($summary['avg_rating'] !== null): ?>
                    Середня оцінка: ★ <?= number_format($summary['avg_rating'], 2, '.', '') ?> / 5
                <?php else: ?>
                    Оцінок ще немає
                <?php endif; ?>
            </span>

            <?= Html::a('Читати та залишити відгук →', ['post/view', 'id' => $post->id], ['class' => 'ms-auto btn btn-sm btn-outline-dark']) ?>
        </div>
    </div>
<?php endforeach; ?>

<div class="mt-4">
    <?= LinkPager::widget([
        'pagination' => $pagination,
        'options' => ['class' => 'pagination'],
        'linkContainerOptions' => ['class' => 'page-item'],
        'linkOptions' => ['class' => 'page-link'],
        'disabledListItemSubTagOptions' => ['class' => 'page-link'],
    ]) ?>
</div>