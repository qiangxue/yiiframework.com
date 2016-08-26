<?php

namespace app\controllers;

use app\components\RowHelper;
use app\models\Auth;
use app\models\News;
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;
use app\models\SignupForm;
use app\models\User;
use Yii;
use yii\authclient\ClientInterface;
use yii\base\InvalidParamException;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\web\NotFoundHttpException;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    /**
     * @param ClientInterface $client
     */
    public function onAuthSuccess($client)
    {
        $attributes = $client->getUserAttributes();
        $email = ArrayHelper::getValue($attributes, 'email');

        /** @var Auth $auth */
        $auth = Auth::find()->where([
            'source' => $client->getId(),
            'source_id' => $attributes['id'],
        ])->one();

        if (Yii::$app->user->isGuest) {
            if ($auth) { // login
                $user = $auth->user;
                Yii::$app->user->login($user, 3600 * 24 * 30);
            } else { // signup
                if (User::find()->where(['email' => $email])->exists()) {
                    Yii::$app->getSession()->setFlash('error', [
                        Yii::t('app', "User with the same email as in {client} account already exists but isn't linked to it. Login using email first to link it.", ['client' => $client->getTitle()]),
                    ]);
                } else {
                    $password = Yii::$app->security->generateRandomString(6);
                    $user = new User([
                        'username' => $attributes['login'],
                        'email' => $email,
                        'password' => $password,
                    ]);
                    $user->generateAuthKey();
                    $user->generatePasswordResetToken();

                    $transaction = $user->getDb()->beginTransaction();

                    if ($user->save()) {
                        $auth = new Auth([
                            'user_id' => $user->id,
                            'source' => $client->getId(),
                            'source_id' => (string)$attributes['id'],
                        ]);
                        if ($auth->save()) {
                            $transaction->commit();
                            Yii::$app->user->login($user, 3600 * 24 * 30);
                        } else {
                            print_r($auth->getErrors());
                            die();
                        }
                    } else {
                        print_r($user->getErrors());
                        die();
                    }
                }
            }
        } else { // user already logged in
            if (!$auth) { // add auth provider
                $auth = new Auth([
                    'user_id' => Yii::$app->user->id,
                    'source' => $client->getId(),
                    'source_id' => $attributes['id'],
                ]);
                $auth->save();
            }
        }
    }

    public function actionIndex()
    {
        $books = array_slice(Yii::$app->params['books2'], 0, 5);
        return $this->render('index', [
            'testimonials' => Yii::$app->params['testimonials'],
            'books' => $books,
            'news' => News::find()->latest()->limit(4)->all(),
        ]);
    }

    /**
     * This action redirects old urls to the new location.
     */
    public function actionRedirect($url)
    {
        $urlMap = [
            'doc/terms' => ['site/license', '#' => 'docs'],
            'about' => ['guide/view', 'type' => 'guide', 'version' => reset(Yii::$app->params['versions']['api']), 'language' => 'en', 'section' => 'intro-yii'],
            'performance' => ['site/index'],
            'demos' => ['site/index'],
            'doc' => ['guide/entry'],
        ];
        if (isset($urlMap[$url])) {
            return $this->redirect($urlMap[$url], 301); // Moved Permanently
        } else {
            throw new NotFoundHttpException('The requested page was not found.');
        }
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user, 3600 * 24 * 30)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionBooks()
    {
        return $this->render('books', ['books2' => Yii::$app->params['books2'], 'books1' => Yii::$app->params['books1']]);
    }

    public function actionContribute()
    {
        return $this->render('contribute');
    }

    public function actionChat()
    {
        return $this->render('chat');
    }

    public function actionLicense()
    {
        return $this->render('license');
    }

    public function actionWiki()
    {
        return $this->render('wiki');
    }

    public function actionTeam()
    {
        $members = Yii::$app->params['members'];

        $activeMembers = [];
        $pastMembers = [];

        foreach ($members as $member) {
            if ($member['active']) {
                $activeMembers[] = $member;
            } else {
                $pastMembers[] = $member;
            }
        }

        $activeMembers = RowHelper::split($activeMembers, 6);
        $pastMembers = RowHelper::split($pastMembers, 6);

        $contributors = false;
        try {
            $data_dir = Yii::getAlias('@app/data');
            $contributors = json_decode(file_get_contents($data_dir . '/contributors.json'), true);
        } catch(\Exception $e) {
            $contributors = false;
        }

        return $this->render('team', [
            'activeMembers' => $activeMembers,
            'pastMembers' => $pastMembers,
            'contributors' => $contributors,
        ]);
    }

    public function actionReportIssue()
    {
        return $this->render('report-issue');
    }

    public function actionSecurity()
    {
        return $this->render('security');
    }

    public function actionDownload()
    {
	    $versions = Yii::$app->params['versions']['minor-versions'];
	    $versionInfo = Yii::$app->params['versions']['version-info'];
        return $this->render('download', [
	        'versions' => $versions,
	        'versionInfo' => $versionInfo,
        ]);
    }

    public function actionTos()
    {
        return $this->render('tos');
    }

    public function actionLogo()
    {
        return $this->render('logo');
    }

    public function actionTour()
    {
        return $this->render('tour');
    }

    public function actionResources()
    {
        return $this->render('resources');
    }

    /**
     * used to download specific files
     */
    public function actionFile($category, $file)
    {
        if (!preg_match('~^[\w\d-.]+$~', $file)) {
            throw new NotFoundHttpException('The requested page was not found.');
        }

        switch($category)
        {
            case 'docs-offline':
                $filePath = Yii::getAlias("@app/data/docs-offline/$file");
                if (file_exists($filePath)) {
                    return Yii::$app->response->sendFile($filePath, $file);
                }
                break;
        }
        throw new NotFoundHttpException('The requested page was not found.');
    }
}
