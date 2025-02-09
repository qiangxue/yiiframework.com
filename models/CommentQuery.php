<?php

namespace app\models;

use yii\db\ActiveQuery;

/**
 * Class CommentQuery
 *
 * @see Comment
 */
class CommentQuery extends ActiveQuery
{
    /**
     * @inheritdoc
     * @return Comment[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Comment|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @return $this
     */
    public function active()
    {
        return $this->andWhere(['status' => Comment::STATUS_ACTIVE]);
    }

    /**
     * @param string $type
     * @param int $id
     * @return $this
     */
    public function forObject($type, $id)
    {
        return $this->andWhere(['object_type' => $type, 'object_id' => $id]);
    }

    /**
     * @param string $type
     * @param int $count
     * @return $this
     */
    public function recentComments($type, $count)
    {
        return $this->andWhere(['object_type' => $type])->orderBy(['created_at' => SORT_DESC])->limit($count);
    }
}
