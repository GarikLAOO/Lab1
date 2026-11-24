<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\LoginForm;
use app\models\SignupForm;
use app\models\Review;

class AuthController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'profile'],
                'rules' => [
                    [
                        'actions' => ['logout', 'profile'],
                        'allow' => true,
                        'roles' => ['@'], 
                    ],
                ],
            ],
        ];
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['auth/profile']);
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            Yii::$app->session->setFlash('success', 'Ви успішно авторизувалися у книжковому блозі!');
            return $this->redirect(['auth/profile']);
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionSignup()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['auth/profile']);
        }

        $model = new SignupForm();

        if ($model->load(Yii::$app->request->post()) && $model->signup() !== null) {
            Yii::$app->session->setFlash('success', 'Реєстрацію успішно завершено! Тепер увійдіть за допомогою свого email та пароля.');
            return $this->redirect(['auth/login']);
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        Yii::$app->session->setFlash('info', 'Ви вийшли з облікового запису.');

        return $this->goHome();
    }

    /**
     * Особистий кабінет авторизованого читача (захищений AccessControl).
     */
    public function actionProfile()
    {
        $user = Yii::$app->user->identity;
        $userReviews = Review::find()
            ->where(['author_email' => $user->email])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        return $this->render('profile', [
            'user' => $user,
            'userReviews' => $userReviews,
        ]);
    }

    /**
     * Перевірка поточного статусу авторизації 
     */
    public function actionTest()
    {
        if (Yii::$app->user->isGuest) {
            return $this->renderContent('<div class="alert alert-warning mt-4">Користувач у гостьовому режимі.</div>');
        }

        $user = Yii::$app->user->identity;
        return $this->renderContent(
            '<div class="alert alert-success mt-4">Користувач авторизований! Логін: <strong>'
            . htmlspecialchars($user->username, ENT_QUOTES, 'UTF-8')
            . '</strong> (' . htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8') . ')</div>'
        );
    }
}