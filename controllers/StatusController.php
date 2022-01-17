<?php

namespace app\controllers;

use app\components\github\GithubRepoStatus;
use Github\Client as GithubClient;
use Yii;
use yii\base\InvalidConfigException;
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
        $packages = ['1.1' => [], '2.0' => [], '3.0' => []];
        $versions = array_keys($packages);

        if (!in_array($version, $versions, true)) {
            throw new NotFoundHttpException('The requested version does not exist.');
        }

        $tokenFile = Yii::getAlias('@app/data') . '/github.token';
        if (!file_exists($tokenFile)) {
            throw new InvalidConfigException("Github token is missing. It must be located in $tokenFile.");
        }

        $token = trim(file_get_contents($tokenFile));
        $client = new GithubClient();
        $client->authenticate($token, null, GithubClient::AUTH_HTTP_TOKEN);

        $packages[$version] = $this->getPackages($client, $version, $packages);

        $githubRepoStatus = new GithubRepoStatus(Yii::$app->getCache(), $client, $packages[$version], $version);
        $data = $githubRepoStatus->getData();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'sort' => [
                'attributes' => ['repository', 'no_release_for', 'latest'],
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

    public function actionYii3Progress()
    {
        $this->layout = 'fullpage';
        $this->sectionTitle = 'How about progress on Yii3 development?';

        $version = '3.0';
        $packages = [$version => []];
        $cacheKey = 'packages_progress' . $version;
        $packagesProgress = Yii::$app->cache->getOrSet($cacheKey, function () use ($version, $packages) {
            $tokenFile = Yii::getAlias('@app/data') . '/github.token';
            if (!file_exists($tokenFile)) {
                throw new InvalidConfigException("Github token is missing. It must be located in $tokenFile.");
            }

            $token = trim(file_get_contents($tokenFile));
            $client = new GithubClient();
            $client->authenticate($token, null, GithubClient::AUTH_HTTP_TOKEN);

            $packages[$version] = $this->getPackages($client, $version, $packages);

            $githubRepoStatus = new GithubRepoStatus(Yii::$app->getCache(), $client, $packages[$version], $version);
            $data = $githubRepoStatus->getData();

            return [
                'all' => count($data),
                'released' => count(array_filter($data, function ($elem) {
                    return !empty($elem['latest']);
                })),
            ];
        }, 60 * 60);

        return $this->render('yii3-progress', [
            'progress' => "{$packagesProgress['released']}/{$packagesProgress['all']}",
            'progressPercent' => $packagesProgress['all'] > 0
                ? round(100 * $packagesProgress['released'] / $packagesProgress['all'])
                : 0,
        ]);
    }

    private function getPackages(GithubClient $client, string $version, array $packages): array
    {
        return Yii::$app->cache->getOrSet('packages' . $version, function () use ($client, $version, $packages) {
            $httpClient = $client->getHttpClient();
            $packagesList = [];
            $i = 1;

            while (!empty($packages)) {
                $response = $httpClient->get(
                    "/orgs/yiisoft/repos?page=$i&per_page=100",
                    ['Accept' => 'application/vnd.github.mercy-preview+json'],
                );
                $packages = json_decode($response->getBody()->getContents());

                foreach ($packages as $package) {
                    if (in_array('yii' . (int) $version, $package->topics, true) && !$package->archived) {
                        $packagesList[] = explode('/', $package->full_name);
                    }
                }

                $i++;
            }

            sort($packagesList);

            return $packagesList;
        }, 60 * 60 * 24);
    }
}
