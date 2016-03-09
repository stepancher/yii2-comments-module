<?php

namespace stepancher\comments\models;

use stepancher\comments\Module;
use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%comments}}".
 *
 * @property integer $id ID
 * @property integer $model_class Model class ID
 * @property integer $model_id Model ID
 * @property integer $author_id Author ID
 * @property string $content Content
 * @property integer $status_id Status
 * @property integer $created_at Create time
 * @property integer $updated_at Update time
 *
 * @property \common\models\User $author Author
 * @property Model $model Model
 */
class Comment extends ActiveRecord
{
    /** Status banned */
    const STATUS_BANNED = 0;
    /** Status active */
    const STATUS_ACTIVE = 1;
    /** Status deleted */
    const STATUS_DELETED = 2;

    const STATUS_MODER = 3;

    /**
     * @var null|array|\yii\db\ActiveRecord[] Comment children
     */
    protected $_children;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%comments}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestampBehavior' => [
                'class' => TimestampBehavior::className()
            ]
        ];
    }

    /**
     * @return array Status array
     */
    public static function getStatusArray()
    {
        return [
            self::STATUS_BANNED => Module::t('comments', 'STATUS_BANNED'),
            self::STATUS_ACTIVE => Module::t('comments', 'STATUS_ACTIVE'),
            self::STATUS_DELETED => Module::t('comments', 'STATUS_DELETED'),
            self::STATUS_MODER => Module::t('comments', 'STATUS_MODER')
        ];
    }

    /**
     * $_children getter.
     *
     * @return null|array|]yii\db\ActiveRecord[] Comment children
     */
    public function getChildren()
    {
        return $this->_children;
    }

    /**
     * $_children setter.
     *
     * @param array|\yii\db\ActiveRecord[] $value Comment children
     */
    public function setChildren($value)
    {
        $this->_children = $value;
    }

    /**
     * @return string Comment status
     */
    public function getStatus()
    {
        return self::getStatusArray()[$this->status_id];
    }

    /**
     * @return boolean Whether comment is active or not
     */
    public function getIsActive()
    {
        return $this->status_id === self::STATUS_ACTIVE;
    }

    /**
     * @return boolean Whether comment is banned or not
     */
    public function getIsBanned()
    {
        return $this->status_id === self::STATUS_BANNED;
    }

    /**
     * @return boolean Whether comment is deleted or not
     */
    public function getIsDeleted()
    {
        return $this->status_id === self::STATUS_DELETED;
    }

    /**
     * @return boolean Whether comment is deleted or not
     */
    public function getIsModer()
    {
        return $this->status_id === self::STATUS_MODER;
    }

    /**
     * Model ID validation.
     *
     * @param string $attribute Attribute name
     * @param array $params Attribute params
     *
     * @return mixed
     */
    public function validateModelId($attribute, $params)
    {
        /** @var ActiveRecord $class */
        $class = Model::findIdentity($this->model_class);

        if ($class === null) {
            $this->addError($attribute, Module::t('comments', 'ERROR_MSG_INVALID_MODEL_ID'));
        } else {
            $model = $class->name;
            if ($model::find()->where(['id' => $this->model_id]) === false) {
                $this->addError($attribute, Module::t('comments', 'ERROR_MSG_INVALID_MODEL_ID'));
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // Require and without html tags
            ['content', 'filter', 'filter' => 'trim'],
            ['content', 'filter', 'filter' => 'strip_tags'],
            ['content', 'required'],
            // Parent ID
            [
                'parent_id',
                'exist',
                'targetAttribute' => 'id',
                'filter' => ['model_id' => $this->model_id, 'model_class' => $this->model_class]
            ],
            // Model class
            ['model_class', 'exist', 'targetClass' => Model::className(), 'targetAttribute' => 'id'],
            // Model
            ['model_id', 'validateModelId'],
            // Content
            ['content', 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('comments', 'ATTR_ID'),
            'parent_id' => Module::t('comments', 'ATTR_PARENT'),
            'model_class' => Module::t('comments', 'ATTR_MODEL_CLASS'),
            'model_id' => Module::t('comments', 'ATTR_MODEL'),
            'author_id' => Module::t('comments', 'ATTR_AUTHOR'),
            'content' => Module::t('comments', 'ATTR_CONTENT'),
            'status_id' => Module::t('comments', 'ATTR_STATUS'),
            'created_at' => Module::t('comments', 'ATTR_CREATED'),
            'updated_at' => Module::t('comments', 'ATTR_UPDATED'),
        ];
    }

    /**
     * @return User
     */
    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClass()
    {
        return $this->hasOne(Model::className(), ['id' => 'model_class']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModel()
    {
        /** @var ActiveRecord $class */
        $class = Model::find()->where(['id' => $this->model_class])->asArray()->one();
        $model = $class->name;

        return $this->hasOne($model::className(), ['id' => 'model_id']);
    }

    /**
     * Get comments tree.
     *
     * @param integer $model Model ID
     * @param integer $class Model class ID
     * @param null $author_id
     * @param null|int $status
     * @param array $options - другие параметры для работы с выбором комментариев
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getTree($model, $class, $author_id = null, $status = null, $options = [])
    {
        $orderBy = empty($options['comments-order']) ? ['created_at' => SORT_DESC, 'parent_id' => SORT_ASC] : $options['comments-order'];

        if ($author_id === null) {
            $models = self::find()->where([
                'model_id' => $model,
                'model_class' => $class,
            ]);
            if (!is_null($status)) {
                $models->andWhere(['status_id' => $status]);
            }
            $models = $models->orderBy($orderBy)
                ->with(['author'])
                ->with(['class'])
                ->all();
        } else {
            $models = self::find()
                ->where([
                    'model_id' => $model,
                    'model_class' => $class,
                    'author_id' => $author_id,
                ])
                ->with(['author'])
                ->orderBy($orderBy)
                ->all();
        }

        if ($models !== null) {
            $models = self::buildTree($models);
        }

        return $models;
    }

    /**
     *  Get comments tree.
     * @param $model
     * @param int|null $author_id
     * @param int|null $status_id
     * @return int|string
     */
    public static function getCountComments($model, $author_id = null, $status_id = null)
    {
        $where = [
            'model_id' => $model->id,
            'model_class' => sprintf("%u", crc32($model::className())),
        ];

        if ($author_id !== null) {
            $where['author_id'] = $author_id;
        }
        if ($status_id !== null) {
            $where['status_id'] = $status_id;
        }else{
            $where['status_id'] = Comment::STATUS_ACTIVE;
        }

        return self::find()->where($where)->count();
    }

    /**
     * Build comments tree.
     *
     * @param array $data Records array
     * @param int $rootID parent_id Root ID
     *
     * @return array|\yii\db\ActiveRecord[] Comments tree
     */
    protected static function buildTree(&$data, $rootID = 0)
    {
        $tree = [];

        foreach ($data as $id => $node) {
            if ($node->parent_id == $rootID) {
                unset($data[$id]);
                $node->children = self::buildTree($data, $node->id);
                $tree[] = $node;
            }
        }

        return $tree;
    }

    /**
     * Delete comment.
     *
     * @return boolean Whether comment was deleted or not
     */
    public function deleteComment()
    {
        $this->status_id = self::STATUS_DELETED;
        $this->content = '';
        return $this->save(false, ['status_id', 'content']);
    }

    /**
     * Возвращаем последний комментарий пользователя
     * @param int $idUser
     * @param model
     * @return array|null|ActiveRecord
     */
    public static function lastUserComment($idUser, $model, $status) {
        $modelId = Model::find()
            ->select('id')
            ->where(['name' => $model::className()])
            ->scalar();
        return Comment::find()
            ->where([
                'author_id' => $idUser,
                'status_id' => $status,
                'model_class' => $modelId,
                'model_id' => $model->id
            ])
            ->orderBy('created_at DESC')
            ->limit(1)
            ->asArray()
            ->one();
    }

    /**
     * Вызываем пользовательский метод - определяется интерфейсом commentableinterface.php
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        $comments_models = Model::find()->where(['id' => $this->model_class])->asArray()->one();


        if(!empty($comments_models['name']) && method_exists($comments_models['name'], 'afterSaveComment')) {
            call_user_func_array([$comments_models['name'], 'afterSaveComment'],
                [$insert, $changedAttributes, $this->getAttributes(), $comments_models]);
        }

        parent::afterSave($insert, $changedAttributes);
    }
}
