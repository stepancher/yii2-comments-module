<?php

namespace stepancher\comments\widgets;

use stepancher\comments\Asset;
use stepancher\comments\models\frontend\Comment;
use stepancher\comments\Module;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\Json;

class Comments extends Widget
{
    /**
     * @var \yii\db\ActiveRecord|null Widget model
     */
    public $model;

    /**
     * @var string прим. "Обсуждение пользователей/Комментарии"
     */
    public $title;

    public $author_id = null;

    /**
     * Может ли пользователь оставлять комментарии
     * @var bool
     */
    public $canComment = true;

    /**
     * @var array Comments Javascript plugin options
     */
    public $jsOptions = [];

    public $viewPath = null;

    /**
     * Сообщения с определенным статусом
     * @var null
     */
    public $status = null;

    /**
     * Опции виджета
     * @see getDefaultOptions
     * @var array
     */
    public $options = [];
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->model === null) {
            throw new InvalidConfigException('The "model" property must be set.');
        }

        $this->options = array_merge($this->getDefaultOptions(), $this->options);

        $this->registerClientScript();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $class = $this->model;
        $class = sprintf("%u", crc32($class::className()));
        $models = Comment::getTree($this->model->id, $class, $this->author_id, $this->status, $this->options);
        $model = new Comment(['scenario' => 'create']);
        $model->model_class = $class;
        $model->model_id = $this->model->id;

        $count = Comment::getCountComments($this->model, $this->author_id);

        $lastUserComment = Comment::lastUserComment(Yii::$app->getUser()->id,
            $this->model, Comment::STATUS_ACTIVE);


        $viewPath = $this->viewPath !== null ? $this->viewPath : Yii::$app->getModule('comments')->widgetViewPath;
        return $this->render($viewPath . 'index', [
            'title' => $this->title,
            'models' => $models,
            'model' => $model,
            'count' => $count,
            'author_id' => $this->author_id,
            'current_model' => $this->model,
            'canComment' => $this->canComment,
            'lastUserComment' => $lastUserComment
        ]);
    }

    /**
     * Параметры по умолчанию
     * @return array
     */
    protected function getDefaultOptions(){
        return Module::getDefaultOptions();
    }

    /**
     * Register widget client scripts.
     */
    protected function registerClientScript()
    {
        $view = $this->getView();
        $options = Json::encode($this->jsOptions);
        Asset::register($view);
        $view->registerJs('jQuery.comments(' . $options . ');');
    }
}
