<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Реєстрація читача — Книжковий блог';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row justify-content-center mt-4">
    <div class="col-md-6">
        <div class="card shadow-sm border-0" style="background: #fdfbf7; border-left: 5px solid #6b4226 !important;">
            <div class="card-body p-4">
                <h1 class="h3 mb-2" style="color: #4a3422;"><?= Html::encode($this->title) ?></h1>
                <p class="text-muted mb-4">
                    Створіть обліковий запис, щоб оцінювати книжкові огляди та переглядати власну історію відгуків.
                </p>

                <?php $form = ActiveForm::begin(['id' => 'signup-form']); ?>

                    <?= $form->field($model, 'username')->textInput([
                        'autofocus' => true,
                        'placeholder' => 'Наприклад, book_lover7',
                    ])->hint('Від 3 до 30 символів: латинські літери, цифри та _') ?>

                    <?= $form->field($model, 'email')->input('email', [
                        'placeholder' => 'reader@example.com',
                    ]) ?>

                    <?= $form->field($model, 'password')->passwordInput([
                        'placeholder' => 'Мінімум 6 символів (літера + цифра)',
                    ])->hint('Щонайменше 6 символів, обов’язково латинська літера та цифра, без пробілів.') ?>

                    <?= $form->field($model, 'password_repeat')->passwordInput([
                        'placeholder' => 'Повторіть пароль',
                    ]) ?>

                    <div class="d-grid gap-2 mt-3">
                        <?= Html::submitButton('Зареєструватися', ['class' => 'btn btn-success', 'name' => 'signup-button']) ?>
                    </div>

                <?php ActiveForm::end(); ?>

                <hr class="my-4">
                <p class="mb-0 text-center">
                    Вже зареєстровані?
                    <?= Html::a('Увійти в акаунт', ['auth/login'], ['class' => 'fw-bold']) ?>
                </p>
            </div>
        </div>
    </div>
</div>