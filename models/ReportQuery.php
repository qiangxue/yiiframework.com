<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Report]].
 *
 * @see Report
 */
class ReportQuery extends \yii\db\ActiveQuery
{
    /**
     * @return $this
     */
    public function open()
    {
        return $this->andWhere(['status' => Report::STATUS_OPEN]);
    }

    /**
     * @return $this
     */
    public function done()
    {
        return $this->andWhere(['status' => Report::STATUS_DONE]);
    }

    /**
     * @inheritdoc
     * @return Report[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Report|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
