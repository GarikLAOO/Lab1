<?php

use yii\helpers\Html;

$this->title = 'Кабінет читача: ' . $user->username;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="card shadow-sm mb-4" style="background: #fdfbf7; border-left: 5px solid #6b4226;">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h1 class="h3 mb-0" style="color: #4a3422;">
                Вітаємо, <?= Html::encode($user->username) ?>!
            </h1>
            <span class="badge bg-success fs-6">Авторизований читач</span>
        </div>

        <hr>

        <ul class="list-unstyled mb-3">
            <li><strong>ID користувача:</strong> <?= Html::encode((string) $user->id) ?></li>
            <li><strong>Логін:</strong> <?= Html::encode($user->username) ?></li>
            <li><strong>Email:</strong> <?= Html::encode($user->email) ?></li>
            <li><strong>Дата реєстрації:</strong> <?= Html::encode($user->created_at) ?></li>
        </ul>

        <div class="d-flex gap-2 flex-wrap">
            <?= Html::a('Перейти до книжкового блогу', ['post/index'], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Статус авторизації (auth/test)', ['auth/test'], ['class' => 'btn btn-outline-secondary']) ?>
            <?= Html::a('Вийти з акаунту', ['auth/logout'], ['class' => 'btn btn-outline-danger']) ?>
        </div>
    </div>
</div>

<h3 class="h5 mb-3">Ваші відгуки на книги (<?= count($userReviews) ?>)</h3>

<?php if (empty($userReviews)): ?>
    <div class="alert alert-info">
        Ви ще не залишали відгуків із цією електронною поштою. Перейдіть до
        <?= Html::a('публікацій блогу', ['post/index']) ?>, щоб оцінити книгу!
    </div>
<?php else: ?>
    <?php foreach ($userReviews as $rev): ?>
        <div class="card mb-2">
            <div class="card-body">
                <strong>Оцінка: <?= (int) $rev->rating ?>/5</strong>
                <span class="text-muted ms-2"><?= Html::encode($rev->created_at) ?></span>
                <p class="mb-0 mt-1"><?= nl2br(Html::encode($rev->comment)) ?></p>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>