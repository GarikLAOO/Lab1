<?php

use yii\helpers\Html;

$this->title = 'Статистика відгуків та рейтинг публікацій';
$this->registerCssFile('@web/css/post.css');
?>

<div class="post-header-bar">
    <h1>Статистика відгуків та рейтинг публікацій</h1>
    <?= Html::a('← До публікацій', ['post/index'], ['class' => 'btn btn-outline-secondary']) ?>
</div>

<p class="text-muted">
    Публікації відсортовано за середньою оцінкою користувачів (від найвищої до найнижчої).
    Обчислення <code>COUNT()</code>, <code>AVG()</code>, <code>MIN()</code>, <code>MAX()</code> та розподілу оцінок виконано на рівні бази даних.
</p>

<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>Рейтинг</th>
                <th>Публікація</th>
                <th class="text-center">Відгуків</th>
                <th class="text-center">Середня оцінка</th>
                <th class="text-center">Min / Max</th>
                <th class="text-center">1★</th>
                <th class="text-center">2★</th>
                <th class="text-center">3★</th>
                <th class="text-center">4★</th>
                <th class="text-center">5★</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($stats as $row): ?>
                <tr>
                    <td class="text-center fw-bold">
                        <?= $row['rank'] !== null ? '#' . $row['rank'] : '—' ?>
                    </td>
                    <td>
                        <?= Html::a(Html::encode($row['title']), ['post/view', 'id' => $row['id']]) ?>
                    </td>
                    <td class="text-center fw-bold"><?= $row['reviews_count'] ?></td>
                    <td class="text-center">
                        <?php if ($row['avg_rating'] !== null): ?>
                            <span class="badge bg-warning text-dark fs-6">
                                ★ <?= number_format($row['avg_rating'], 2, '.', '') ?>
                            </span>
                        <?php else: ?>
                            <span class="text-muted">Ще немає відгуків</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <?php if ($row['min_rating'] !== null): ?>
                            <?= $row['min_rating'] ?> / <?= $row['max_rating'] ?>
                        <?php else: ?>
                            —
                        <?php endif; ?>
                    </td>
                    <?php for ($star = 1; $star <= 5; $star++): ?>
                        <td class="text-center">
                            <?= $row['distribution'][$star] ?>
                        </td>
                    <?php endfor; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>