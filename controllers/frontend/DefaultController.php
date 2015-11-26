<?php

namespace stepancher\comments\controllers\frontend;

use stepancher\comments\models\frontend\Comment;
use stepancher\comments\models\Model;
use stepancher\comments\Module;
use ubasma\rating\models\Rate;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Default controller.
 */
class DefaultController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'create' => ['post'],
                'update' => ['put', 'post'],
                'delete' => ['post', 'delete']
            ]
        ];

        return $behaviors;
    }

    /**
     * Create comment.
     */
    public function actionCreate()
    {
        $model = new Comment(['scenario' => 'create']);
        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $modelClass = Model::findOne(['id' => Yii::$app->request->post('Comment')['model_class']]);

                //Проверяем может ли пользователь оставлять комментарий
                if(method_exists($modelClass->name, 'checkCanComment')) {
                    $canComment = call_user_func_array([$modelClass->name, 'checkCanComment'],
                        [Yii::$app->request->post('Comment')['model_id'], Yii::$app->getUser()->id]);
                } else {
                    $canComment = true;
                }

                if ($canComment && $model->save(false)) {

                    if(Yii::$app->getModule('comments')->allowRate && Yii::$app->request->post('rating')!==null) {
                        if(!Yii::$app->request->post('Comment')['parent_id']) {

                            $modelDestination = call_user_func([$modelClass->name, 'findOne'],
                                [Yii::$app->request->post('Comment')['model_id']]);

                            call_user_func_array([Yii::$app->getModule('comments')->ratePath . 'Rate', 'setRating'],
                                [$modelDestination, Yii::$app->request->post('rating'), $model]);
                            /*Rate::setRating($modelDestination,
                                Yii::$app->request->post('rating'), $model);*/
                        }
                    }
                    return $this->tree($model);
                } else {
                    Yii::$app->response->setStatusCode(500);
                    return Module::t('comments', 'FRONTEND_FLASH_FAIL_CREATE');
                }
            } elseif (Yii::$app->request->isAjax) {
                Yii::$app->response->setStatusCode(400);
                return ActiveForm::validate($model);
            }
        }
    }

    /**
     * Update comment.
     *
     * @param integer $id Comment ID
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->setScenario('update');
        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->save(false)) {
                    return $model->content;
                } else {
                    Yii::$app->response->setStatusCode(500);
                    return Module::t('comments', 'FRONTEND_FLASH_FAIL_UPDATE');
                }
            } elseif (Yii::$app->request->isAjax) {
                Yii::$app->response->setStatusCode(400);
                return ActiveForm::validate($model);
            }
        }
    }

    /**
     * Delete comment page.
     *
     * @param integer $id Comment ID
     *
     * @return string Comment content
     */
    public function actionDelete($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($this->findModel($id)->deleteComment()) {
            return Module::t('comments', 'FRONTEND_WIDGET_COMMENTS_DELETED_COMMENT_TEXT');
        } else {
            Yii::$app->response->setStatusCode(500);
            return Module::t('comments', 'FRONTEND_FLASH_FAIL_DELETE');
        }
    }

    /**
     * Find model by ID.
     *
     * @param integer|array $id Comment ID
     *
     * @return Comment Model
     *
     * @throws HttpException 404 error if comment not found
     */
    protected function findModel($id)
    {
        /** @var Comment $model */
        $model = Comment::findOne($id);

        if ($model !== null) {
            return $model;
        } else {
            throw new HttpException(404, Module::t('comments', 'FRONTEND_FLASH_RECORD_NOT_FOUND'));
        }
    }

    /**
     * @param Comment $model Comment
     *
     * @return string Comments list
     */
    protected function tree($model)
    {
        $viewPath =  Yii::$app->request->post('viewPath');
        $author_id =  Yii::$app->request->post('author_id');
        if($viewPath===null){
            $viewPath = Yii::$app->getModule('comments')->widgetViewPath;
        }
        $models = Comment::getTree($model->model_id, $model->model_class, $author_id);
        return $this->renderPartial($viewPath.'_index_item', ['models' => $models]);
    }
}
