<?php

namespace app\controllers;

use app\components\UserPermissions;
use app\jobs\ExtensionImportJob;
use app\models\File;
use app\models\Star;
use app\models\Extension;
use app\models\ExtensionCategory;
use app\models\ExtensionTag;
use app\notifications\ExtensionNewFileNotification;
use app\notifications\ExtensionUpdateNotification;
use League\Flysystem\FileNotFoundException;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\queue\Queue;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

class ExtensionController extends BaseController
{
    public $sectionTitle = 'Yii Framework Extensions';
    public $headTitle = 'Extensions';

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
                        'actions' => ['index', 'view', 'files', 'download'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create', 'list-tags', 'update', 'update-packagist', 'keep-alive', 'delete-file'],
                        'roles' => ['@'],
                    ],
//                    [
//                        // allow all to a access index and view action
//                        'allow' => true,
//                        'actions' => ['admin', 'create', 'update', 'delete', 'list-tags'],
//                        'roles' => ['news:pAdmin'],
//                    ],
                ]
            ],

            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'update-packagist' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex($category = null, $tag = null, $version = '2.0')
    {
        if (!in_array($version, [Extension::YII_VERSION_10, Extension::YII_VERSION_11, Extension::YII_VERSION_20], true)) {
            throw new NotFoundHttpException();
        }

        $query = Extension::find()->active()->with(['owner', 'category']);

        $categoryModel = null;
        if ($category !== null) {
            $categoryId = (int) $category;
            if (($categoryModel = ExtensionCategory::findOne($categoryId)) === null) {
                throw new NotFoundHttpException('The requested category does not exist.');
            }
            $query->andWhere(['category_id' => $categoryModel->id]);
        }

        $tagModel = null;
        if ($tag !== null) {
            $tagModel = ExtensionTag::findOne(['slug' => $tag]);
            if ($tagModel === null) {
                throw new NotFoundHttpException('The requested tag does not exist.');
            }
            $query->joinWith('tags', false);
            $query->andWhere(['extension_tag_id' => $tagModel->id]);
        }

        if ($version) {
            $query->andWhere(['like', 'yii_version', $version]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'attributes'=> [
                    'create'=> [
                        'asc'=>['created_at' => SORT_ASC],
                        'desc'=>['created_at' => SORT_DESC],
                        'label'=>'Sorted by date',
                        'default'=>'desc',
                    ],
                    'update'=> [
                        'asc'=>['updated_at' => SORT_ASC],
                        'desc'=>['updated_at' => SORT_DESC],
                        'label'=>'Sorted by date (updated)',
                        'default'=>'desc',
                    ],
                    'rating'=> [
                        'asc'=>['rating' => SORT_ASC],
                        'desc'=>['rating' => SORT_DESC],
                        'label'=>'Sorted by rating',
                        'default'=>'desc',
                    ],
                    'comments'=> [
                        'asc'=>['comment_count' => SORT_ASC],
                        'desc'=>['comment_count' => SORT_DESC],
                        'label'=>'Sorted by comments',
                        'default'=>'desc',
                    ],
                    'downloads'=> [
                        'asc'=>['download_count' => SORT_ASC],
                        'desc'=>['download_count' => SORT_DESC],
                        'label'=>'Sorted by downloads',
                        'default'=>'desc',
                    ],
                ],
                'defaultOrder'=> ['create'=>SORT_DESC],
            ],
            'pagination' => [
                'pageSize' => 12,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'tag' => $tagModel,
            'category' => $categoryModel,
            'version' => $version,
        ]);
    }

    public function actionView($name, $vendorName = null)
    {
        if ($vendorName) {
            $name = "$vendorName/$name";
        }
        $model = $this->findModel($name);

        // normalize URL, redirect non-case sensitive URLs
        if ($model->name !== $name) {
            return $this->redirect($model->getUrl(), 301);
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionCreate()
    {
        if (!UserPermissions::canAddOrUpdateExtension()) {
            Yii::$app->session->addFlash('warning', 'Please confirm your email.');
            return $this->redirect(['/user/profile']);
        }

        $model = new Extension();
        $model->initDefaults();
        $post = Yii::$app->request->post('Extension', []);
        if (isset($post['from_packagist'])) {

            $model->from_packagist = (int) $post['from_packagist'];
            $model->scenario = 'create_' . ($model->from_packagist == 1 ? 'packagist' : 'custom');

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {

                if ($model->from_packagist) {

                    // TODO validate github user name of developer
                    // Yii::$app->user->getIdentity()->getGithub();

                    $model->populatePackagistName();
                    $model->description = null;
                    $model->license_id = null;
                    $model->save(false);

                    /** @var $queue Queue */
                    $queue = Yii::$app->queue;
                    $queue->push(new ExtensionImportJob(['extensionId' => $model->id]));
                } else {
                    $model->save(false);
                }

                Star::castStar($model, Yii::$app->user->id, 1);
                return $this->redirect($model->getUrl());
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModelById($id);

        if (!UserPermissions::canAddOrUpdateExtension()) {
            Yii::$app->session->addFlash('warning', 'Please confirm your email.');
            return $this->redirect(['/user/profile']);
        }

        if (!UserPermissions::canUpdateExtension($model)) {
            throw new ForbiddenHttpException('You are not allowed to perform this operation.');
        }

        $model->scenario = 'update_' . ($model->from_packagist == 1 ? 'packagist' : 'custom');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            if (!$model->from_packagist) {
                // notification email for followers
                $model->refresh();
                ExtensionUpdateNotification::create([
                    'extension' => $model,
                    'updater' => Yii::$app->user->identity,
                ]);
            }

            Star::castStar($model, Yii::$app->user->id, 1);
            return $this->redirect($model->getUrl());
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionUpdatePackagist($id)
    {
        $model = $this->findModelById($id);

        if (!UserPermissions::canAddOrUpdateExtension()) {
            Yii::$app->session->addFlash('warning', 'Please confirm your email.');
            return $this->redirect(['/user/profile']);
        }

        if (!UserPermissions::canUpdateExtension($model)) {
            throw new ForbiddenHttpException('You are not allowed to perform this operation.');
        }

        $model->updateAttributes(['update_status' => Extension::UPDATE_STATUS_EXPIRED]);

        // allow one update every 5min
        $delay = $model->update_time ? max(0, strtotime($model->update_time) - strtotime('now - 5 minutes')) : 0;
        /** @var $queue Queue */
        $queue = Yii::$app->queue;
        $job = new ExtensionImportJob(['extensionId' => $model->id]);
        if ($delay > 0) {
            $queue->delay($delay)->push($job);
        } else {
            $queue->push($job);
        }

        $link = $delay < 15 ? ' ' . Html::a('refresh!', $model->getUrl()) : '';
        Yii::$app->session->setFlash('success', 'Update for extension scheduled to be performed ' . Yii::$app->formatter->asRelativeTime($delay, 0) . '.' . $link);

        return $this->redirect($model->getUrl());
    }

    /**
     * actionList to return matched tags
     */
    public function actionListTags($query)
    {
        $models = ExtensionTag::find()->where(['like', 'name', $query])->all();
        $items = [];

        foreach ($models as $model) {
            $items[] = ['name' => $model->name];
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $items;
    }

    /**
     * Just reply with a 'pong' to the session keep alive call.
     * This method is only accessable for logged in users, so session will be opened.
     * @return string
     */
    public function actionKeepAlive()
    {
        return 'pong';
    }

    public function actionFiles($id)
    {
        $model = $this->findModelById($id);

        if (UserPermissions::canUpdateExtension($model)) {
            $file = new File();
            if ($file->load(Yii::$app->request->post())) {
                $file->object_type = $model->getObjectType();
                $file->object_id = $model->getObjectId();
                $file->file_name = UploadedFile::getInstance($file, 'file_name');
                if ($file->save()) {

                    // notification email for followers
                    $file->refresh();
                    ExtensionNewFileNotification::create([
                        'extension' => $model,
                        'updater' => Yii::$app->user->identity,
                        'file' => $file,
                    ]);

                    return $this->refresh();
                }
            }
        } else {
            $file = null;
        }

        return $this->render('files', [
            'model' => $model,
            'files' => $model->downloads,
            'file' => $file,
        ]);
    }

    public function actionDownload($filename, $name, $vendorName = null)
    {
        if ($vendorName) {
            $name = "$vendorName/$name";
        }
        $model = $this->findModel($name);

        // normalize URL, redirect non-case sensitive URLs
        if ($model->name !== $name) {
            return $this->redirect($model->getUrl('download', ['filename' => $filename]), 301);
        }

        /** @var $file File */
        $file = $model->getDownloads()->where(['file_name' => $filename])->one();
        if ($file === null) {
            throw new NotFoundHttpException('The requested file does not exist.');
        }
        try {
            return $file->download();
        } catch (FileNotFoundException $e) {
            throw new NotFoundHttpException('The requested file does not exist.', 0, $e);
        }
    }


    public function actionDeleteFile($id, $file)
    {
        $model = $this->findModelById($id);

        if (UserPermissions::canAddOrUpdateExtension()) {
            Yii::$app->session->addFlash('warning', 'Please confirm your email.');
            return $this->redirect(['/user/profile']);
        }

        if (UserPermissions::canUpdateExtension($model)) {
            throw new ForbiddenHttpException('You are not allowed to perform this operation.');
        }

        $download = $model->getDownloads()->where(['id' => $file])->one();
        if ($download === null) {
            throw new NotFoundHttpException('The requested file does not exist.');
        }
        $download->delete();

        return $this->redirect(['files', 'id' => $model->id]);
    }




    /**
     * Finds the Extension model based on its name.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $name
     * @return Extension the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($name)
    {
        if (($model = Extension::find()->where(['name' => $name])->active()->one()) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Finds the Extension model based on its name.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Extension the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelById($id)
    {
        if (($model = Extension::find()->where(['id' => $id])->active()->one()) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
