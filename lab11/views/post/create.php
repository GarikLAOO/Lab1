<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Нова публікація блогу';
$this->registerCssFile('@web/css/post.css');
?>

<div class="post-header-bar">
    <h1>Створення нової публікації</h1>
    <?= Html::a('← До списку публікацій', ['post/index'], ['class' => 'btn btn-outline-secondary']) ?>
</div>

<div class="post-card">
    <p class="text-muted mb-3">
        Перед збереженням система автоматично очистить зайві пробіли, перевірить довжину заголовка (5–150 символів)
        та обсяг тексту (щонайменше 10 слів). Абзаци зберігаються, а HTML-символи безпечно екрануються під час виведення.
    </p>

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'title')->textInput([
            'maxlength' => 150,
            'placeholder' => 'Наприклад: Огляд книги «Атомні звички» Джеймса Кліра',
        ]) ?>

        <?= $form->field($model, 'content')->textarea([
            'rows' => 7,
            'placeholder' => 'Введіть текст публікації (мінімум 10 слів, абзаци зберігаються)...',
        ]) ?>

        <?= $form->field($model, 'published')->checkbox() ?>

        <div class="form-group mt-3">
            <?= Html::submitButton('Перевірити та зберегти', ['class' => 'btn btn-primary']) ?>
        </div>

    <?php ActiveForm::end(); ?>
</div>