<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $post['title'];
$this->registerCss("
    .blog-post-box {
        background: #fdfbf7;
        border: 1px solid #dfd3c3;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 28px;
    }
    .blog-meta {
        color: #7c6652;
        font-size: 0.92rem;
        margin-bottom: 14px;
    }
    .comment-form-box {
        background: #ffffff;
        border: 1px solid #e5e5e5;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.05);
    }
    .comment-form-box .help-block {
        color: #dc3545;
        font-size: 0.9rem;
        margin-top: 4px;
    }
    .star-rating {
        display: inline-flex;
        flex-direction: row-reverse;
        gap: 6px;
        font-size: 2rem;
    }
    .star-rating input {
        display: none;
    }
    .star-rating label {
        color: #d0d0d0;
        cursor: pointer;
        transition: color 0.15s ease;
        margin-bottom: 0;
    }
    .star-rating input:checked ~ label,
    .star-rating label:hover,
    .star-rating label:hover ~ label {
        color: #f5a623;
    }
");
?>

<div class="blog-post-box">
    <h1><?= Html::encode($post['title']) ?></h1>
    <div class="blog-meta">
        <strong>Автор книги:</strong> <?= Html::encode($post['book_author']) ?> |
        <strong>Жанр:</strong> <?= Html::encode($post['genre']) ?> |
        <strong>Дата допису:</strong> <?= Html::encode($post['published_at']) ?>
    </div>
    <p><?= nl2br(Html::encode($post['content'])) ?></p>
</div>

<div class="comment-form-box">
    <h3>Залишити відгук про допис</h3>

    <?php if ($saveError !== null): ?>
        <div class="alert alert-danger">
            <?= Html::encode($saveError) ?>
        </div>
    <?php endif; ?>

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'author')->textInput([
            'maxlength' => 50,
            'placeholder' => 'Ваше ім’я (2–50 символів)',
        ]) ?>

        <?= $form->field($model, 'rating')->radioList(
            [5 => '★', 4 => '★', 3 => '★', 2 => '★', 1 => '★'],
            [
                'class' => 'star-rating',
                'item' => function ($index, $label, $name, $checked, $value) {
                    $id = 'rating-star-' . $value;
                    $checkedAttr = $checked ? 'checked' : '';
                    return "<input type=\"radio\" id=\"{$id}\" name=\"{$name}\" value=\"{$value}\" {$checkedAttr}>"
                        . "<label for=\"{$id}\" title=\"{$value} із 5\">{$label}</label>";
                },
            ]
        ) ?>

        <?= $form->field($model, 'comment')->textarea([
            'rows' => 5,
            'maxlength' => 1000,
            'placeholder' => 'Поділіться враженнями про книгу (від 10 до 1000 символів)...',
        ]) ?>

        <div class="form-group mt-3">
            <?= Html::submitButton('Надіслати відгук', ['class' => 'btn btn-primary']) ?>
        </div>

    <?php ActiveForm::end(); ?>
</div>