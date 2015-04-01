<?php

/**
 * Comments list view.
 *
 * @var \yii\base\View $this View
 * @var \yii\data\ActiveDataProvider $dataProvider Data provider
 * @var \stepancher\comments\models\backend\CommentSearch $searchModel Search model
 * @var array $statusArray Status array
 * @var array $modelArray Model array
 */

use yii\grid\GridView;
use stepancher\comments\Module;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\jui\DatePicker;

$this->title = Module::t('comments', 'BACKEND_INDEX_TITLE');
$this->params['subtitle'] = Module::t('comments', 'BACKEND_INDEX_SUBTITLE');
$this->params['breadcrumbs'] = [
    $this->title
];
?>
<div class="box">
    <div class="row">
        <div class="col-xs-12">
            <?= GridView::widget([
                'id' => 'comments-grid',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'layout' => "<div class='box-body'>{items}</div><div class='box-footer'><div class='row'><div class='col-sm-6'>{summary}</div><div class='col-sm-6'>{pager}</div></div></div>",
                'columns' => [
                    'id',
                    'parent_id',
                    'model_id',
                    [
                        'attribute' => 'model_class',
                        'value' => function ($model) {
                            return $model->class->name;
                        },
                        'filter' => Html::activeDropDownList(
                            $searchModel,
                            'model_class',
                            $modelArray,
                            [
                                'class' => 'form-control',
                                'prompt' => Module::t('comments', 'BACKEND_PROMPT_MODEL_CLASS')
                            ]
                        )
                    ],
                    'content',
                    [
                        'attribute' => 'status_id',
                        'format' => 'html',
                        'value' => function ($model) {
                            $class = ($model->status_id === $model::STATUS_ACTIVE) ? 'label-success' : 'label-danger';

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
                        'class' => ActionColumn::className(),
                        'template' => '{update}{delete}'
                    ]
                ]
            ]); ?>
        </div>
    </div>
</div>