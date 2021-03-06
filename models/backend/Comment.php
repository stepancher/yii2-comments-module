<?php

namespace stepancher\comments\models\backend;

/**
 * This is the model class for table "{{%comments}}".
 *
 * @property integer $id ID
 * @property integer $model_class Model class ID
 * @property integer $model_id Model ID
 * @property integer $author_id Author ID
 * @property string  $content Content
 * @property integer $status_id Status
 * @property integer $created_at Create time
 * @property integer $updated_at Update time
 * @property bool $moderator Update comment
 *
 * @property \stepancher\users\models\User $author Author
 * @property Model $model Model
 */
class Comment extends \stepancher\comments\models\Comment
{
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['admin-update'] = ['status_id', 'content'];

        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {

        if (parent::beforeSave($insert)) {
            //если модератор исправил текст сообщения пользователя, ставим moderator = true
            $oldComment = $this->findOne($this->id);

            if($this->scenario == 'admin-update' && $oldComment->content !== $this->content) {
                $this->moderator = true;
            }

            return true;
        } else {
            return false;
        }
    }

}
