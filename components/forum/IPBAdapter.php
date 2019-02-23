<?php

namespace app\components\forum;

use app\models\User;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\db\Connection;
use yii\db\Query;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

/**
 * IPBAdapter implements a forum bridge between the IPB 3.1 and the application.
 * Configure as follows:
 *
 * ```php
 * 'forumBridge' => [
 *      'class' => \app\components\forum\IPBBridge::class,
 *      'db' => 'forumDb',
 *      'tablePrefix' => 'ipb_',
 *  ],
 * ```
 */
class IPBAdapter extends Component implements ForumAdapterInterface
{
    const GROUP_VALIDATING = 1;
    const GROUP_MEMBERS = 3;

    /**
     * @var Connection|array|string the DB connection object or the application component ID of the DB connection.
     */
    public $db = 'db';

    /**
     * @var string IPB table prefix
     */
    public $tablePrefix = 'ipb_';

    /**
     * @var int group to add user to
     */
    public $group = self::GROUP_VALIDATING;

    /**
     * Initializes the ForumBridge component.
     * This method will initialize the [[db]] property to make sure it refers to a valid DB connection.
     * @throws InvalidConfigException if [[db]] is invalid.
     */
    public function init()
    {
        parent::init();
        $this->db = Instance::ensure($this->db, Connection::class);
    }

    public function getPostDate($user, $number)
    {
        if (!$user->forum_id) {
            return false;
        }

        $tablePrefix = $this->tablePrefix;
        $n = ((int) $number) - 1;
        $sql = "SELECT post_date FROM {$tablePrefix}posts WHERE author_id = :user_id ORDER BY post_date ASC LIMIT " . ($n > 0 ? "$n," : '') . '1';
        $cmd = $this->db->createCommand($sql, [':user_id' => $user->forum_id]);
        return $cmd->queryScalar();
    }

    public function getPostCount($user)
    {
        if (!$user->forum_id) {
            return 0;
        }

        $tablePrefix = $this->tablePrefix;
        $sql = "SELECT count(*) FROM {$tablePrefix}posts WHERE author_id = :user_id";
        $cmd = $this->db->createCommand($sql, [':user_id' => $user->forum_id]);
        return $cmd->queryScalar();
    }

    public function getPostCounts()
    {
        $tablePrefix = $this->tablePrefix;
        $sql = "SELECT member_id, posts FROM {$tablePrefix}members";
        return ArrayHelper::map($this->db->createCommand($sql)->queryAll(),'member_id','posts');
    }

    public function getPostCountsByUsername()
    {
        $tablePrefix = $this->tablePrefix;
        $sql = "SELECT `name`, posts FROM {$tablePrefix}members";
        return ArrayHelper::map($this->db->createCommand($sql)->queryAll(),'name','posts');
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
        $ipbSalt = $this->generateIPBPasswordSalt();

        $forumUserId = (new Query())
            ->select('member_id')
            ->from($this->tablePrefix . 'members')
            ->where(['email' => $user->email])
            ->scalar($this->db);

        if ($forumUserId) {
            return $forumUserId;
        }

        $username = $user->username;
        $displayName = !empty($user->display_name) ? $user->display_name : $user->username;

        $now = time();

        $this->db->createCommand()->insert($this->tablePrefix . 'members', [
            'name' => $username,
            'members_l_username' => mb_strtolower($username, \Yii::$app->charset),

            'members_display_name' => $displayName,
            'members_l_display_name' => mb_strtolower($displayName, \Yii::$app->charset),

            'members_seo_name' => Inflector::transliterate($displayName),

            'member_login_key' => $this->generateIPBAutoLoginKey(),
            'member_login_key_expire' => $now + 86400,

            'email' => $user->email,

            'member_group_id' => $this->group,

            'joined' => $now,
            'last_visit' => $now,
            'last_activity' => $now,

            'ip_address' => $this->getCurrentIp(),
            'allow_admin_mails' => 1,
            'hide_email' => 1,
            'language' => 1,

            'members_pass_hash' => $this->getIPBPasswordHash($ipbSalt, $password),
            'members_pass_salt' => $ipbSalt,
        ])->execute();

        return $this->db->getLastInsertID();
    }

    public function changeUserPassword(User $user, $password)
    {
        $ipbSalt = $this->generateIPBPasswordSalt();

        $this->db->createCommand()->update($this->tablePrefix . 'members', [
            'members_pass_hash' => $this->getIPBPasswordHash($ipbSalt, $password),
            'members_pass_salt' => $ipbSalt,
        ], [
            'member_id' => $user->forum_id
        ])->execute();
    }

    /**
     * Generates a password salt.
     * Returns n length string of any char except backslash
     *
     * Taken from IPB 3.1
     *
     * @param int $len Length of desired salt, 5 by default
     * @return string n character random string
     */
    private function generateIPBPasswordSalt($len = 5)
    {
        $salt = '';

        for ($i = 0; $i < $len; $i++) {
            $num = random_int(33, 126);

            if ($num == '92') {
                $num = 93;
            }

            $salt .= chr($num);
        }

        return $salt;
    }

    /**
     * Generates a log in key
     *
     * Taken from IPB 3.1
     *
     * @param int $len Length of desired random chars to MD5
     * @return string MD5 hash of random characters
     */
    private function generateIPBAutoLoginKey($len = 60)
    {
        $pass = $this->generateIPBPasswordSalt($len);
        return md5($pass);
    }

    /**
     * Get IPB-compatible password hash
     *
     * @param string $ipbSalt
     * @param string $plainPassword
     * @return string
     */
    private function getIPBPasswordHash($ipbSalt, $plainPassword)
    {
        $plainPassword = User::parseLegacyPasswordValue($plainPassword);
        return md5(md5($ipbSalt) . md5($plainPassword));
    }

    private function getCurrentIp()
    {
        return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    }

    /**
     * List of badges provided by the forum
     * @return array
     */
    public function getForumBadges()
    {
        return [];
    }

    public function getForumUserId(User $user)
    {
        return $user->forum_id;
    }
}
