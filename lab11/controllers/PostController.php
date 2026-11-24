<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\Pagination;
use app\models\Post;
use app\models\Review;
use app\models\PostStatsService;

class PostController extends Controller
{
    /**
     * Завдання 1 + відображення кількості відгуків і середньої оцінки біля кожного поста.
     */
    public function actionIndex()
    {
        $query = Post::find();

        $pagination = new Pagination([
            'defaultPageSize' => 5,
            'totalCount' => $query->count(),
        ]);

        $posts = $query->orderBy(['title' => SORT_ASC])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        $postIds = array_map(fn($p) => $p->id, $posts);
        $statsService = new PostStatsService();
        $summaryMap = $statsService->getSummaryByPostIds($postIds);

        return $this->render('index', [
            'posts' => $posts,
            'pagination' => $pagination,
            'summaryMap' => $summaryMap,
        ]);
    }

    /**
     * Завдання 2: створення публікації з попереднім очищенням і валідацією.
     */
    public function actionCreate()
    {
        $model = new Post();
        $model->published = 1;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Публікацію успішно перевірено та збережено в базі даних!');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Перегляд окремої публікації та додавання відгуку в таблицю reviews.
     */
    public function actionView($id)
    {
        $post = Post::findOne($id);
        if ($post === null) {
            throw new NotFoundHttpException('Публікацію не знайдено.');
        }

        $review = new Review();
        $review->post_id = $post->id;

        if ($review->load(Yii::$app->request->post()) && $review->save()) {
            Yii::$app->session->setFlash('success', 'Ваш відгук збережено в базі даних!');
            return $this->redirect(['view', 'id' => $post->id]);
        }

        $reviews = $post->getReviews()->orderBy(['created_at' => SORT_DESC])->all();

        return $this->render('view', [
            'post' => $post,
            'reviews' => $reviews,
            'reviewModel' => $review,
        ]);
    }

    /**
     * Завдання 4: сторінка статистики відгуків і рейтингу публікацій.
     */
    public function actionStats()
    {
        $statsService = new PostStatsService();
        $stats = $statsService->getFullStatistics();

        return $this->render('stats', [
            'stats' => $stats,
        ]);
    }
}