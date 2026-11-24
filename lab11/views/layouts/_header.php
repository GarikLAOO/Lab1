<?php

declare(strict_types=1);

/** @var yii\web\View $this */

use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Html;

$items = [
    [
        'label' => 'Home',
        'url' => ['/site/index'],
    ],
    [
        'label' => 'About',
        'url' => ['/site/about'],
    ],
    [
        'label' => 'Contact',
        'url' => ['/site/contact'],
    ],
    Yii::$app->user->isGuest
        ? ['label' => 'Реєстрація', 'url' => ['/auth/signup']]
        : ['label' => 'Кабінет (' . Yii::$app->user->identity->username . ')', 'url' => ['/auth/profile']],
    Yii::$app->user->isGuest
        ? ['label' => 'Вхід', 'url' => ['/auth/login']]
        : '<li class="nav-item">'
            . Html::beginForm(['/auth/logout'])
            . Html::submitButton(
                'Вихід',
                ['class' => 'nav-link btn btn-link logout']
            )
            . Html::endForm()
            . '</li>',
    ['label' => 'Анкета', 'url' => ['/site/entry']],
    ['label' => 'Блог', 'url' => ['/post/index']],
    ['label' => 'Додати пост', 'url' => ['/post/create']],
    ['label' => 'Статистика', 'url' => ['/post/stats']],
    ['label' => 'Відгук про книгу', 'url' => ['/site/comment']],

];

?>
<header id="header">
    <?php NavBar::begin(
        [
            'brandLabel' => Yii::$app->name,
            'brandUrl' => Yii::$app->homeUrl,
            'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top']
        ],
    ) ?>
    <?= Nav::widget(
        [
            'options' => ['class' => 'navbar-nav me-auto'],
            'encodeLabels' => false,
            'items' => $items,
        ],
    ) ?>
    <?= Html::button(
        '&#127769;',
        [
            'id' => 'theme-toggle',
            'class' => 'btn btn-link nav-link fs-5',
            'aria-label' => 'Switch to dark mode',
        ],
    ) ?>
    <?php NavBar::end() ?>
</header>
