<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\data\Pagination;

use app\models\comment\Comment,
	app\models\comment\CommentForm,
	app\models\comment\CommentAttach;

class CommentController extends Controller {

	/**
	 * @inheritdoc
	 */
	public function actions() {
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
			'captcha' => [
				'class' => 'yii\captcha\CaptchaAction',
				'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
			],
		];
	}

	/**
	 * List comments, show form, save new comment
	 * @return string
	 */
	public function actionIndex() {
		$model = new CommentForm();
		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			$model->files = UploadedFile::getInstances($model, 'files');
			if($model->validate()) {
				$model->save();
				foreach($model->files as $file) {
					CommentAttach::upload($file, $model->id);
				}
			}
		}
		
		$query = Comment::find()->where(['parent' => null])->orderBy(['id' => SORT_ASC]);
		$cnQ = clone $query;
		$page = new Pagination(['totalCount' => $cnQ->count(), 'defaultPageSize' => 5]);
		$models = $query->with('commentAttaches')->offset($page->offset)->limit($page->limit)->all();
		
		
		return $this->render('index', [
			'model' => $model,
			'models' => $models,
			'page' => $page,
		]);
	}

	/**
	 * Login action.
	 *
	 * @return Response|string
	 */
	public function actionLogin() {
		if (!Yii::$app->user->isGuest) {
			return $this->goHome();
		}

		$model = new LoginForm();
		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			return $this->goBack();
		}
		return $this->render('login', [
					'model' => $model,
		]);
	}

	/**
	 * Logout action.
	 *
	 * @return Response
	 */
	public function actionLogout() {
		Yii::$app->user->logout();

		return $this->goHome();
	}

	/**
	 * Displays contact page.
	 *
	 * @return Response|string
	 */
	public function actionContact() {
		$model = new ContactForm();
		if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
			Yii::$app->session->setFlash('contactFormSubmitted');

			return $this->refresh();
		}
		return $this->render('contact', [
					'model' => $model,
		]);
	}

	/**
	 * Displays about page.
	 *
	 * @return string
	 */
	public function actionAbout() {
		return $this->render('about');
	}

}
