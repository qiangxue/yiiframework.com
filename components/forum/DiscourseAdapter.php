<?php

namespace app\components\forum;

use app\models\User;
use Yii;
use yii\base\Component;
use yii\httpclient\Client;

/**
 * DiscourseAdapter implements a forum bridge between the Discourse Forum and the application.
 * Configure as follows:
 *
 * ```php
 * 'forumBridge' => [
 *      'class' => \app\components\forum\DiscourseAdapter::class,
 *      'apiUrl' => 'https://forum.yiiframework.com/',
 *      'apiToken' => '123456',
 *  ],
 * ```
 *
 * @see https://docs.discourse.org/
 */
class DiscourseAdapter extends Component implements ForumAdapterInterface
{
    /**
     * @var string discourse API URL.
     */
    public $apiUrl;
    /**
     * @var string discourse API auth token.
     */
    public $apiToken;
    /**
     * @var string discourse API user name for requests that need admin permission.
     */
    public $apiAdminUser = 'system';


    /**
     * @return Client
     */
    private function getClient()
    {
        return new Client([
            'baseUrl' => $this->apiUrl,
        ]);
    }


    public function getPostDate($user, $number)
    {
        return 0;
    }

    /**
     * @param User $user
     * @return int
     */
    public function getPostCount($user)
    {
        $this->getForumUserId($user);

        $response = $this->getClient()->get(
            $url = sprintf('/admin/users/%d.json', $user->forum_id),
            null,
            [
                'Api-Key' => $this->apiToken,
                'Api-Username' => $this->apiAdminUser
            ]
        )->send();
        if (!$response->isOk) {
            Yii::error("Discourse API request returned error: $url");
            Yii::error($response);
            return 0;
        }
        $userData = $response->data;
        return $userData['user']['post_count'] ?? 0;
    }

    /**
     * Normalizes username according to Discourse rules
     * @see https://github.com/discourse/discourse/blob/master/lib/user_name_suggester.rb
     *
     * @param string $username
     * @return string
     */
    public function normalizeUsername(string $username): string
    {
        $username = preg_replace('/[^\w.-]/', '_', $username);
        $username = preg_replace('/^[^\w\p{M}_]+/u', '', $username);
        $username = preg_replace('/\.(js|json|css|htm|html|xml|jpg|jpeg|png|gif|bmp|ico|tif|tiff|woff)$/i', '_', $username);
        $username = preg_replace('/[^\w\p{M}]+$/u', '', $username);
        $username = preg_replace('/[-_.]{2,}/', '_', $username);
        return $username;
    }

    public function getForumUserId(User $user)
    {
        if ($user->forum_id) {
            return $user->forum_id;
        }

        $response = $this->getClient()
            ->get(
                $url = sprintf('/u/by-external/%s.json', $user->id),
                null,
                [
                    'Api-Key' => $this->apiToken,
                    'Api-Username' => $this->apiAdminUser
                ]
            )
            ->send();

        if ($response->isOk) {
            $userData = $response->data;
            if (isset($userData['user']['id'])) {
                $user->updateAttributes(['forum_id' => $userData['user']['id']]);
                return $userData['user']['id'];
            }
        }

        if ($response->statusCode != 404) {
            Yii::error("Discourse API request returned invalid response: $url");
            Yii::error($response);
        }
        return null;
    }

    public function getPostCounts()
    {
        // not implemented for discourse
        return null;
    }

    public function getPostCountsByUsername()
    {
        $postCounts = [];

        $url = '/directory_items.json?period=all&order=post_count';
        while (true) {
            $response = $this->getClient()
                ->get(
                    $url,
                    null,
                    [
                        'Api-Key' => $this->apiToken,
                        'Api-Username' => $this->apiAdminUser
                    ]
                )->send();
            if (!$response->isOk) {
                Yii::error('Discourse API request returned error: ' . $url);
                Yii::error($response);
                return $postCounts;
            }
            $userData = $response->data;

            foreach($userData['directory_items'] as $item) {
                $postCounts[$item['user']['username']] = $item['topic_count'] + $item['post_count'];
            }

            if (!isset($userData['load_more_directory_items'])) {
                break;
            }
            // workaround Discourse bug https://meta.discourse.org/t/directory-items-json-api-returns-wrong-link-for-next-page/96268
            $url = str_replace('?', '.json?', $userData['load_more_directory_items']);
        }

        return $postCounts;
    }

    /**
     * Creates forum user
     *
     * @param User $user
     * @param string $password
     * @return int forum user ID
     */
    public function ensureForumUser(User $user, $password)
    {
        // forum users are created via SSO
        return null;
    }

    public function changeUserPassword(User $user, $password)
    {
        // forum users are created via SSO, there is no password in discourse
    }

    /**
     * List of badges provided by the forum
     * @return array
     */
    public function getForumBadges()
    {
        $badges = Yii::$app->cache->get('discourse_badges');
        if ($badges === false) {
            $response = $this->getClient()->get(
                '/admin/badges.json',
                null,
                [
                    'Api-Key' => $this->apiToken,
                    'Api-Username' => $this->apiAdminUser
                ]
        )->send();
            if (!$response->isOk) {
                Yii::error('Discourse API request returned error: /admin/badges.json');
                Yii::error($response);
                return [];
            }
            $badges = $response->data['badges'] ?? [];
            foreach($badges as $b => $badge) {
                if (!$badge['enabled']) {
                    unset($badges[$b]);
                    continue;
                }
                // make relative URLs absolute in badge description
                $badges[$b]['description'] = preg_replace('~<a href="/([^"]+)"~', '<a href="'.rtrim($this->apiUrl,'/').'/\1"', $badge['description']);
                $badges[$b]['url'] = rtrim($this->apiUrl,'/').'/badges/' . $badge['id'] . '/' . $badge['slug'];
            }
            Yii::$app->cache->set('discourse_badges', $badges, 1800);
        }
        return $badges;
    }
}
