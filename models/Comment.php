<?php

namespace app\models;

use app\components\objectKey\ObjectKeyHelper;
use app\components\objectKey\ObjectKeyInterface;
use Yii;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "comment".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $object_type
 * @property string $object_id
 * @property string $text
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property int $total_votes
 * @property int $up_votes
 * @property float $rating
 *
 * @property User $user
 */
class Comment extends ActiveRecord implements ObjectKeyInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    /**
     * @var string[] Available object types for comments.
     */
    public static $availableObjectTypes = [ObjectKeyHelper::TYPE_WIKI, ObjectKeyHelper::TYPE_EXTENSION];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%comment}}';
    }

    /**
     * @return CommentQuery
     */
    public static function find()
    {
        return Yii::createObject(CommentQuery::class, [get_called_class()]);
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => $this->timeStampBehavior(),
            'blameable' => [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'user_id',
                'updatedByAttribute' => false,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['text'], 'required'],
            [['text'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'text' => Yii::t('app', 'Text'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return ActiveRecord
     */
    public function getModel()
    {
        if (!in_array($this->getObjectType(), static::$availableObjectTypes, true)) {
            return null;
        }

        /** @var ActiveRecord $modelClass */
        $modelClass = ObjectKeyHelper::getClassByObject($this);
        return $modelClass::findOne($this->getObjectId());
    }

    /**
     * @return string
     */
    public function getObjectType()
    {
        return $this->object_type;
    }

    /**
     * @return string
     */
    public function getObjectId()
    {
        return $this->object_id;
    }
}
