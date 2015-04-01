<?php

/**
 * @var \yii\base\View $this View
 * @var \yii\data\ActiveDataProvider $dataProvider Data provider
 * @var \stepancher\comments\models\backend\CommentSearch $searchModel Search model
 * @var array $statusArray Statuses array
 */

use stepancher\comments\Module;
use yii\grid\GridView;

use yii\grid\ActionColumn;
use yii\helpers\Html;

$this->title = Module::t('comments-models', 'BACKEND_INDEX_TITLE');
$this->params['subtitle'] = Module::t('comments-models', 'BACKEND_INDEX_SUBTITLE');
$this->params['breadcrumbs'] = [
    $this->title
];

?>

<div class="page-header">
    <div class="panel-heading-controls">
        <?= Html::a('<i class="btn-label icon fa fa-plus"></i> '.Module::t('comments-models','BACKEND_CREATE_SUBTITLE'), ['create'], ['class' => 'btn btn-labeled btn-primary no-margin-t']) ?>
    </div>
</div>
<div class="box">
    <div class="row">
        <div class="col-xs-12">
            <?= GridView::widget([
                'id' => 'comments-models-grid',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'layout' => "<div class='box-body'>{items}</div><div class='box-footer'><div class='row'><div class='col-sm-6'>{summary}</div><div class='col-sm-6'>{pager}</div></div></div>",
                'columns' => [
                    'id',
                    'name',
                    [
                        'attribute' => 'status_id',
                        'format' => 'html',
                        'value' => function ($model) {
                            $class = ($model->status_id === $model::STATUS_ENABLED) ? 'label-success' : 'label-danger';

                            return '<span class="label ' . $class . '">' . $model->status . '</span>';
                        },
                        'filter' => Html::activeDropDownList(
                            $searchModel,
                            'status_id',
                            $statusArray,
                            [
                                'class' => 'form-control',
                                'prompt' => Module::t('comments', 'BACKEND_PROMPT_STATUS')
                            ]
                        )
                    ],
                    [
                        'attribute' => 'created_at',
                        'format' => 'date',
                    ],
                    [
                        'attribute' => 'updated_at',
                        'format' => 'date',
                    ],
                    [
                        'class' =>ActionColumn::className(),
                        'template' => '{update}{delete}',
                    ],
                ]
            ]); ?>
        </div>
    </div>
</div>