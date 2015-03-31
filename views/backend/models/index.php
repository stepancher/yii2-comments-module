<?php

/**
 * @var \yii\base\View $this View
 * @var \yii\data\ActiveDataProvider $dataProvider Data provider
 * @var \stepancher\comments\models\backend\CommentSearch $searchModel Search model
 * @var array $statusArray Statuses array
 */

use stepancher\base\helpers\System;
use stepancher\comments\models\backend\Model;
use stepancher\comments\Module;
use stepancher\themes\admin\widgets\Box;
use stepancher\themes\admin\widgets\GridView;
use yii\grid\ActionColumn;
use yii\grid\CheckboxColumn;
use yii\helpers\Html;
use yii\jui\DatePicker;

$this->title = Module::t('comments-models', 'BACKEND_INDEX_TITLE');
$this->params['subtitle'] = Module::t('comments-models', 'BACKEND_INDEX_SUBTITLE');
$this->params['breadcrumbs'] = [
    $this->title
];
$gridId = 'comments-models-grid';
$gridConfig = [
    'id' => $gridId,
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        [
            'class' => CheckboxColumn::classname()
        ],
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
            'filter' => DatePicker::widget(
                [
                    'model' => $searchModel,
                    'attribute' => 'created_at',
                    'options' => [
                        'class' => 'form-control'
                    ],
                    'clientOptions' => [
                        'dateFormat' => 'dd.mm.yy',
                    ]
                ]
            )
        ],
        [
            'attribute' => 'updated_at',
            'format' => 'date',
            'filter' => DatePicker::widget(
                [
                    'model' => $searchModel,
                    'attribute' => 'updated_at',
                    'options' => [
                        'class' => 'form-control'
                    ],
                    'clientOptions' => [
                        'dateFormat' => 'dd.mm.yy',
                    ]
                ]
            )
        ]
    ]
];

$boxButtons = $actions = [];
$showActions = false;


$boxButtons[] = '{create}';

$actions[] = '{update}';
$showActions = $showActions || true;
$boxButtons[] = '{batch-delete}';
$actions[] = '{delete}';
$showActions = $showActions || true;


if ($showActions === true) {
    $gridConfig['columns'][] = [
        'class' => ActionColumn::className(),
        'template' => implode(' ', $actions)
    ];
}
$boxButtons = !empty($boxButtons) ? implode(' ', $boxButtons) : null; ?>


<div class="row">
    <div class="col-sm-12">
        <?php Box::begin(
            [
                'title' => Module::t('comments-models', 'BACKEND_INDEX_TITLE_ENABLING'),
                'options' => [
                    'class' => 'box-primary'
                ]
            ]
        ); ?>
        <?php if (Yii::$app->base->hasExtension('blogs')) : ?>
            <?php if (Model::isExtensionEnabled('blogs')) : ?>
                <?= Html::a(
                    Html::tag(
                        'span',
                        Html::tag(
                            'i',
                            '',
                            [
                                'class' => 'fa fa-check'
                            ]
                        ),
                        [
                            'class' => 'badge bg-green'
                        ]
                    ) .
                    Html::tag(
                        'i',
                        '',
                        [
                            'class' => 'fa fa-book'
                        ]
                    ) .
                    Module::t('comments-models', 'BACKEND_INDEX_MODULE_BLOGS'),
                    [
                        '/comments/models/disable',
                        'name' => 'blogs'
                    ],
                    [
                        'class' => 'btn btn-app',
                        'data-method' => 'post',
                        'data-confirm' => Module::t('comments-models', 'BACKEND_INDEX_MODULES_DISABLE_CONFIRMATION')
                    ]
                ) ?>
            <?php else : ?>
                <?= Html::a(
                    Html::tag(
                        'span',
                        Html::tag(
                            'i',
                            '',
                            [
                                'class' => 'fa fa-times'
                            ]
                        ),
                        [
                            'class' => 'badge bg-red'
                        ]
                    ) .
                    Html::tag(
                        'i',
                        '',
                        [
                            'class' => 'fa fa-book'
                        ]
                    ) .
                    Module::t('comments-models', 'BACKEND_INDEX_MODULE_BLOGS'),
                    [
                        '/comments/models/enable',
                        'name' => 'blogs'
                    ],
                    [
                        'class' => 'btn btn-app',
                        'data-method' => 'post'
                    ]
                ) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?php Box::end(); ?>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <?php Box::begin(
            [
                'title' => $this->params['subtitle'],
                'bodyOptions' => [
                    'class' => 'table-responsive'
                ],
                'buttonsTemplate' => $boxButtons,
                'grid' => $gridId
            ]
        ); ?>
        <?= GridView::widget($gridConfig); ?>
        <?php Box::end(); ?>
    </div>
</div>