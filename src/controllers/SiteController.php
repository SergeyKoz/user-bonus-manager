<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\forms\LoginForm;
use app\models\forms\TransferForm;
use app\models\search\UserSearch;
use app\models\User;
use app\services\TransferServiceInterface;

class SiteController extends Controller
{
    /**
     * @var TransferServiceInterface Money transfer service
     */
    private TransferServiceInterface $transferService;

    /**
     * SiteController constructor.
     * @param $id
     * @param $module
     * @param TransferServiceInterface $transferService
     * @param array $config
     */
    public function __construct($id, $module, TransferServiceInterface $transferService, $config = [])
    {
        $this->transferService = $transferService;
        parent::__construct($id, $module, $config);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['withdraw', 'replenish', 'logout'],
                'rules' => [
                    [
                        'actions' => ['replenish', 'withdraw', 'logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays replenish page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionReplenish($id)
    {
        $post = Yii::$app->request->post();
        $model = new TransferForm(
            $this->findModel(Yii::$app->user->id)->username,
            $this->findModel($id)->username,
            $this->transferService);
        $model->scenario = TransferForm::SCENARIO_REPLENISH;

        if ($model->load($post) && $model->validate() && $model->transfer()) {
            Yii::$app->session->setFlash('success','Successfully replenished');
            return $this->redirect(['index']);
        } else {
            return $this->render('replenish', [
                'model' => $model
            ]);
        }
    }

    /**
     * Displays withdraw page.
     * @param integer $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionWithdraw($id)
    {
        $post = Yii::$app->request->post();
        $model = new TransferForm(
            $this->findModel($id)->username,
            $this->findModel(Yii::$app->user->id)->username,
            $this->transferService);
        $model->scenario = TransferForm::SCENARIO_WITHDRAW;

        if ($model->load($post) && $model->validate() && $model->transfer()) {
            Yii::$app->session->setFlash('success','Successfully replenished');
            return $this->redirect(['index']);
        } else {
            return $this->render('withdraw', [
                'model' => $model
            ]);
        }
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
