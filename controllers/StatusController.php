<?php

namespace app\controllers;

use app\components\github\GithubRepoStatus;
use Yii;
use yii\data\ArrayDataProvider;
use yii\web\NotFoundHttpException;

class StatusController extends BaseController
{
    public $sectionTitle = 'Release Statuses';

    /**
     * @param string $version
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionIndex($version = '2.0')
    {
        $packages = [
            '1.1' => [],
            '2.0' => [],
            '3.0' => [],
        ];

        $versions = array_keys($packages);

        if (!in_array($version, $versions, true)) {
            throw new NotFoundHttpException('The requested version does not exist.');
        }

        $client = new \Github\Client();
        $tokenFile = Yii::getAlias('@app/data') . '/github.token';
        if (file_exists($tokenFile)) {
            $token = trim(file_get_contents($tokenFile));
            $client->authenticate($token, null, \Github\Client::AUTH_HTTP_TOKEN);
        }

        $packages[$version] = $this->getPackages($client, $version, $packages);

        $githubRepoStatus = new GithubRepoStatus(Yii::$app->getCache(), $client, $packages[$version], $version);

        $data = $githubRepoStatus->getData();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'sort' => [
                'attributes' => [
                    'repository',
                    'no_release_for',
                    'latest',
                ],
                'defaultOrder' => ['repository' => SORT_ASC],
            ],
            'pagination' => false,
        ]);

        return $this->render('index', [
            'version' => $version,
            'dataProvider' => $dataProvider,
            'versions' => $versions,
        ]);
    }

    private function getPackages($client, $version, $packages)
    {
        return Yii::$app->cache->getOrSet('packages' . $version, function () use ($client, $version, $packages) {
            $packagesList = [];
            $i = 1;
            try {
                $httpClient = $client->getHttpClient();
                while (!empty($packages)) {
                    $response = $httpClient
                        ->get("/orgs/yiisoft/repos?page=$i&per_page=100", ['Accept' => 'application/vnd.github.mercy-preview+json']);
                    $packages = json_decode($response->getBody()->getContents());
                    foreach ($packages as $package) {
                        if (in_array('yii' . (int)$version, $package->topics, true)) {
                            $packagesList[] = explode('/', $package->full_name);
                        }
                    }
                    if ($response->getStatusCode() !== 200) {
                        break;
                    }
                    $i++;
                }
                sort($packagesList);
            } catch (\Exception $e) {
                return $packages[$version];
            }

            return $packagesList;
        }, 60 * 60 * 24);
    }
}
