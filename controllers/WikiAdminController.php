<?php

namespace app\controllers;

use app\components\UserPermissions;
use app\models\search\SearchableBehavior;
use app\models\Wiki;
use app\models\WikiSearch;
use Yii;
use yii\base\Event;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * WikiAdminController implements the CRUD actions for Wiki model.
 */
class WikiAdminController extends BaseController
{
    public $layout = 'admin';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
   		        'class' => AccessControl::class,
   		        'rules' => [
   			        [
   				        // allow all to a access index and view action
   				        'allow' => true,
   				        'actions' => ['index', 'view', 'update'],
                        'roles' => [UserPermissions::PERMISSION_MANAGE_WIKI],
   			        ],
   		        ]
   	        ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Wiki models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WikiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Wiki model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Updates an existing Wiki model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = Wiki::SCENARIO_ADMIN;
        if ($model->load(Yii::$app->request->post())) {
            $model->updateAttributes([
                'status' => $model->status,
            ]);
            /** @var $searchAble SearchableBehavior */
            $searchAble = $model->getBehavior('search');
            $searchAble->afterUpdate(new Event(['sender' => $model]));
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Wiki model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
//    public function actionDelete($id)
//    {
//        $this->findModel($id)->delete();
//
//        return $this->redirect(['index']);
//    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Wiki the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Wiki::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
