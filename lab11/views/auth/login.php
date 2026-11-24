<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Авторизація — Книжковий блог';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row justify-content-center mt-4">
    <div class="col-md-6">
        <div class="card shadow-sm border-0" style="background: #fdfbf7; border-left: 5px solid #6b4226 !important;">
            <div class="card-body p-4">
                <h1 class="h3 mb-2" style="color: #4a3422;"><?= Html::encode($this->title) ?></h1>
                <p class="text-muted mb-4">Введіть свою електронну пошту та пароль, щоб увійти до кабінету читача.</p>

                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                    <?= $form->field($model, 'email')->input('email', [
                        'autofocus' => true,
                        'placeholder' => 'reader@example.com',
                    ]) ?>

                    <?= $form->field($model, 'password')->passwordInput([
                        'placeholder' => 'Введіть пароль',
                    ]) ?>

                    <?= $form->field($model, 'rememberMe')->checkbox() ?>

                    <div class="d-grid gap-2 mt-3">
                        <?= Html::submitButton('Увійти', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                    </div>

                <?php ActiveForm::end(); ?>

                <hr class="my-4">
                <p class="mb-0 text-center">
                    Ще не маєте облікового запису?
                    <?= Html::a('Зареєструватися', ['auth/signup'], ['class' => 'fw-bold']) ?>
                </p>
            </div>
        </div>
    </div>
</div>