<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $post->title;
$this->registerCssFile('@web/css/post.css');
?>

<div class="post-header-bar">
    <h1><?= Html::encode($post->title) ?></h1>
    <?= Html::a('← До списку публікацій', ['post/index'], ['class' => 'btn btn-outline-secondary']) ?>
</div>

<div class="post-card">
    <div class="post-text">
        <?= nl2br(Html::encode($post->content)) ?>
    </div>
    <div class="post-meta-bar">
        <?php if ($post->published && $post->published_at): ?>
            <span>Опубліковано: <strong><?= Html::encode($post->published_at) ?></strong></span>
        <?php else: ?>
            <span class="badge bg-secondary">Не опубліковано</span>
        <?php endif; ?>
        <span>Кількість відгуків: <strong><?= count($reviews) ?></strong></span>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-7">
        <h3>Відгуки читачів</h3>
        <?php if (empty($reviews)): ?>
            <div class="alert alert-info">Для цієї публікації ще немає жодного відгуку. Станьте першим!</div>
        <?php else: ?>
            <?php foreach ($reviews as $rev): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <strong><?= Html::encode($rev->author_name) ?></strong>
                            <span style="color: #e69500;">
                                <?= str_repeat('★', (int) $rev->rating) . str_repeat('☆', 5 - (int) $rev->rating) ?>
                                (<?= (int) $rev->rating ?>/5)
                            </span>
                        </div>
                        <small class="text-muted"><?= Html::encode($rev->author_email) ?> · <?= Html::encode($rev->created_at) ?></small>
                        <p class="mt-2 mb-0"><?= nl2br(Html::encode($rev->comment)) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="col-md-5">
        <div class="card">
            <div class="card-header"><strong>Додати відгук до БД</strong></div>
            <div class="card-body">
                <?php $form = ActiveForm::begin(); ?>
                    <?= $form->field($reviewModel, 'author_name')->textInput(['placeholder' => 'Ваше ім’я']) ?>
                    <?= $form->field($reviewModel, 'author_email')->input('email', ['placeholder' => 'reader@example.com']) ?>
                    <?= $form->field($reviewModel, 'rating')->dropDownList([
                        5 => '5 — Відмінно ★★★★★',
                        4 => '4 — Добре ★★★★☆',
                        3 => '3 — Задовільно ★★★☆☆',
                        2 => '2 — Погано ★★☆☆☆',
                        1 => '1 — Дуже погано ★☆☆☆☆',
                    ], ['prompt' => 'Оберіть оцінку...']) ?>
                    <?= $form->field($reviewModel, 'comment')->textarea(['rows' => 4, 'placeholder' => 'Ваш відгук...']) ?>
                    <?= Html::submitButton('Зберегти відгук', ['class' => 'btn btn-primary mt-2']) ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>