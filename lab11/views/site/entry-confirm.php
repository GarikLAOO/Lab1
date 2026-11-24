<?php

use yii\helpers\Html;

$this->title = 'Результат опрацювання анкети';
$this->registerCss("
    .confirm-card {
        max-width: 580px;
        margin: 28px auto;
        padding: 32px;
        background: #fdfbf7;
        border-left: 6px solid #6b4226;
        border-radius: 12px;
        box-shadow: 0 10px 24px rgba(74, 52, 34, 0.10);
    }
    .confirm-list {
        list-style: none;
        padding: 0;
        margin: 20px 0;
    }
    .confirm-list li {
        padding: 10px 14px;
        margin-bottom: 10px;
        background: #f4efe6;
        border-radius: 8px;
        color: #3a291b;
    }
");
?>

<div class="confirm-card">
    <h2>Ви вказали наступну інформацію:</h2>

    <ul class="confirm-list">
        <li><strong>Ім’я:</strong> <?= Html::encode($model->name) ?></li>
        <li><strong>Адреса електронної пошти:</strong> <?= Html::encode($model->email) ?></li>
        <li><strong>Прочитано книг за рік:</strong> <?= Html::encode((string) $model->booksPerYear) ?></li>
        <li><strong>Середній темп:</strong> <?= Html::encode((string) $booksPerMonth) ?> книг/місяць</li>
        <li><strong>Категорія читача:</strong> <?= Html::encode($readerCategory) ?></li>
    </ul>

    <?= Html::a('Повернутися до форми', ['site/entry'], ['class' => 'btn btn-outline-secondary']) ?>
</div>