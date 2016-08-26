<?php

namespace frontend\controllers;

use app\models\ReturnUser;
use frontend\models\FormModel;
use Yii;
use app\models\ReturnForm;
use app\models\ReturnFormSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FormController implements the CRUD actions for ReturnForm model.
 */
class FormController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Получение списка записей
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ReturnFormSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render(
            'index',
            [
                'searchModel'  => $searchModel,
                'dataProvider' => $dataProvider,
            ]
        );
    }

    /**
     * Получение записи
     *
     * @param integer $id Номер записи
     *
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]
        );
    }

    /**
     * Создание записи
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ReturnForm();

        if($model->load(Yii::$app->request->post()) && $model->save())
        {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        else
        {
            return $this->render(
                'create',
                [
                    'model' => $model,
                ]
            );
        }
    }

    /**
     * Проверка количества запросов с одного Ip
     *
     * @return bool
     */
    protected function checkIp()
    {
        if(2 == Yii::$app->getCache()->get(md5($_SERVER['REMOTE_ADDR'])))
        {
            Yii::$app->session->setFlash('error', 'Слишком много запросов с вашего адреса, пожалуйста подождите минуту');
            return false;
        }

        /** увеличиваем счетчик */
        $count = empty(Yii::$app->getCache()->get(md5($_SERVER['REMOTE_ADDR'])))
            ? 1
            : Yii::$app->getCache()->get(md5($_SERVER['REMOTE_ADDR'])) + 1;

        Yii::$app->getCache()->set(md5($_SERVER['REMOTE_ADDR']), $count, 60);

        return true;
    }

    /**
     * Проверка количества запросов с одного Ip
     *
     * @param string $email Почтовый ящик
     * @return bool
     */
    protected function checkEmail($email)
    {
        if(2 == Yii::$app->getCache()->get(md5($email)))
        {
            Yii::$app->session->setFlash('error', 'Слишком много сообщений на один адрес, пожалуйста подождите минуту');
            return false;
        }

        /** увеличиваем счетчик */
        $count = empty(Yii::$app->getCache()->get(md5($email)))
            ? 1
            : Yii::$app->getCache()->get(md5($email)) + 1;

        Yii::$app->getCache()->set(md5($email), $count, 60);

        return true;
    }

    /**
     * Отправление формы обратной связи
     *
     * @return string|\yii\web\Response
     */
    public function actionSend()
    {
        $model = new FormModel;
        if($model->load(Yii::$app->request->post()) && $model->validate())
        {
            /** проверка количества запросов с одного ip */
            if($this->checkIp())
            {
                return $this->refresh();
            }

            /** проверка количества форм на один email */
            if($this->checkEmail($model->email))
            {
                return $this->refresh();
            }

            $returnUser = new ReturnUser;
            $returnForm = new ReturnForm;

            $user = $returnUser->search(['email' => $model->email]);

            if(empty($user))
            {
                $returnUser->name = $model->name;
                $returnUser->email = $model->email;

                $returnUser->insert();
                $user = $returnUser->search(['email' => $model->email]);
            }

            $returnForm->user_id = $user->id;
            $returnForm->body = $model->body;
            $returnForm->datetime = date('Y-m-d h:i:s');

            $returnForm->insert();

            if($model->sendEmail(Yii::$app->params['email'], $user->id))
            {
                Yii::$app->session->setFlash('success', 'Отзыв отправлен');
            }
            else
            {
                Yii::$app->session->setFlash('error', 'Не удалось отправить отзыв');
            }

            return $this->refresh();
        }
        else
        {
            return $this->render(
                'send',
                [
                    'model' => $model,
                ]
            );
        }
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error'   => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class'           => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Updates an existing ReturnForm model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if($model->load(Yii::$app->request->post()) && $model->save())
        {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        else
        {
            return $this->render('update', [
                'model' => $model,
            ]
            );
        }
    }

    /**
     * Deletes an existing ReturnForm model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ReturnForm model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return ReturnForm the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if(($model = ReturnForm::findOne($id)) !== null)
        {
            return $model;
        }
        else
        {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
