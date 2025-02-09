<?php


namespace app\controllers;


use app\models\Report;
use app\models\ReportContentForm;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;

class ReportController extends BaseController
{
    public $sectionTitle = 'Content reports';
    public $headTitle = 'Content reports';

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
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param string $type
     * @param int $id
     * @return string
     * @throws BadRequestHttpException
     */
    public function actionCreate($type, $id)
    {
        $this->sectionTitle = 'Report content';
        $this->headTitle = 'Report content';

        $report = new Report();
        $report->object_id = $id;
        $report->object_type = $type;

        $object = $report->getObject();

        if ($object === null) {
            throw new BadRequestHttpException('There is no such object.');
        }

        $content = Yii::$app->request->post('content');
        if ($content) {
            $report->content = $content;
            if ($report->save()) {
                Yii::$app->session->setFlash('success', 'Report received. Thank you!');
                Yii::$app->response->redirect(Url::previous());
            }
        }

        return $this->render('create', [
            'report' => $report,
            'object' => $object,
        ]);
    }
}