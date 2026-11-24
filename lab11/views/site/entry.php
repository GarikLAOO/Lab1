<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Книжковий клуб — Анкета читача';
$this->registerCss("
    .book-card {
        max-width: 580px;
        margin: 28px auto;
        padding: 32px;
        background: linear-gradient(145deg, #fdfbf7, #f3ece0);
        border: 1px solid #d9cbb8;
        border-radius: 14px;
        box-shadow: 0 10px 28px rgba(74, 52, 34, 0.10);
    }
    .book-card h1 {
        color: #4a3422;
        font-size: 1.65rem;
        margin-bottom: 8px;
    }
    .book-card .subtitle {
        color: #7c6652;
        margin-bottom: 22px;
    }
    .book-card .help-block {
        color: #dc3545;
        font-size: 0.9rem;
        margin-top: 4px;
    }
    .btn-book {
        background-color: #6b4226;
        border-color: #6b4226;
        color: #fff;
        padding: 10px 24px;
        border-radius: 8px;
        font-weight: 600;
    }
    .btn-book:hover {
        background-color: #52311b;
        color: #fff;
    }
");
?>

<div class="book-card">
    <h1>Книжковий дайджест (Варіант 7)</h1>
    <p class="subtitle">Заповніть форму, щоб отримати персональну підбірку книг на пошту та статистику читання.</p>

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'name')->textInput(['placeholder' => 'Наприклад, Олександр']) ?>

        <?= $form->field($model, 'email')->input('email', ['placeholder' => 'reader@example.com']) ?>

        <?= $form->field($model, 'booksPerYear')->input('number', ['min' => 0, 'max' => 500, 'placeholder' => 'Наприклад, 18']) ?>

        <div class="form-group mt-3">
            <?= Html::submitButton('Надіслати дані', ['class' => 'btn btn-book']) ?>
        </div>

    <?php ActiveForm::end(); ?>
</div>