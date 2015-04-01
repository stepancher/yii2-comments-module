<?php

namespace stepancher\comments\controllers\backend;

use stepancher\comments\models\backend\Model;
use stepancher\comments\models\backend\ModelSearch;
use stepancher\comments\Module;
use Yii;
use yii\filters\VerbFilter;
use yii\web\HttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\filters\AccessControl;
/**
 * Comments models backend controller.
 */
class ModelsController extends \yii\web\Controller
{
    /**
     * @inheritdoc
     */

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index','update','create','delete', 'batch-delete','enable', 'disable'],
                        'roles' => ['admin']
                    ]
                ]
            ],
            'verbs' =>[
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['get'],
                    'view' => ['get'],
                    'create' => ['get', 'post'],
                    'update' => ['get', 'put', 'post'],
                    'delete' => ['post', 'delete'],
                    'batch-delete' => ['post', 'delete'],
                    'enable' => ['post'],
                    'disable' => ['post']
                ]
            ]
        ];
    }


    /**
     * Comment models list page.
     */
    public function actionIndex()
    {
        $searchModel = new ModelSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        $statusArray = Model::getStatusArray();

        return $this->render('index', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
                'statusArray' => $statusArray
            ]);
    }

    /**
     * Create model page.
     */
    public function actionCreate()
    {
        $model = new Model(['scenario' => 'admin-create']);
        $statusArray = Model::getStatusArray();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->save(false)) {
                    return $this->redirect(['index']);
                } else {
                    Yii::$app->session->setFlash('danger', Module::t('comments-models', 'BACKEND_FLASH_FAIL_ADMIN_CREATE'));
                    return $this->refresh();
                }
            } elseif (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        return $this->render('create', [
                'model' => $model,
                'statusArray' => $statusArray
            ]);
    }

    /**
     * Update model page.
     *
     * @param integer $id Post ID
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->setScenario('admin-update');
        $statusArray = Model::getStatusArray();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->save(false)) {
                    return $this->redirect(['index']);
                } else {
                    Yii::$app->session->setFlash('danger', Module::t('comments-models', 'BACKEND_FLASH_FAIL_ADMIN_UPDATE'));
                    return $this->refresh();
                }
            } elseif (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        return $this->render('update', [
                'model' => $model,
                'statusArray' => $statusArray
            ]);
    }

    /**
     * Delete model page.
     *
     * @param integer $id Post ID
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Enable comments for indicated extension
     *
     * @param string $name Extension name
     *
     * @return mixed
     */
    public function actionEnable($name)
    {
        if (!Model::enableExtension($name)) {
            Yii::$app->session->setFlash('danger', Module::t('comments-models', 'BACKEND_FLASH_FAIL_ADMIN_ENABLE'));
        }

        return $this->redirect(['index']);
    }

    /**
     * Disable comments for indicated extension
     *
     * @param string $name Extension name
     *
     * @return mixed
     */
    public function actionDisable($name)
    {
        if (!Model::disableExtension($name)) {
            Yii::$app->session->setFlash('danger', Module::t('comments-models', 'BACKEND_FLASH_FAIL_ADMIN_DISABLE'));
        }

        return $this->redirect(['index']);
    }

    /**
     * Find model by ID.
     *
     * @param integer|array $id Model ID
     *
     * @return \stepancher\comments\models\backend\Model Model
     *
     * @throws HttpException 404 error if model not found
     */
    protected function findModel($id)
    {
        if (is_array($id)) {
            /** @var \stepancher\comments\models\backend\Model $model */
            $model = Model::findAll($id);
        } else {
            /** @var \stepancher\comments\models\backend\Model $model */
            $model = Model::findOne($id);
        }
        if ($model !== null) {
            return $model;
        } else {
            throw new HttpException(404);
        }
    }
}
