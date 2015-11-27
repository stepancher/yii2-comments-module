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

use kartik\grid\GridView;
use kartik\dynagrid\DynaGrid;
use stepancher\comments\Module;
use yii\helpers\Html;
use yii\jui\DatePicker;

$this->title = Module::t('comments', 'BACKEND_INDEX_TITLE');
$this->params['subtitle'] = Module::t('comments', 'BACKEND_INDEX_SUBTITLE');
$this->params['breadcrumbs'] = [
    $this->title
];

$columns = [
    ['class' => 'kartik\grid\SerialColumn', 'order' => DynaGrid::ORDER_FIX_LEFT],
    'parent_id',
    'model_id',
    [
        'attribute' => 'model_class',
        'value' => function ($model) {
            return $model->class->name;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $modelArray,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => '---'],
    ],
    'content',
    [
        'attribute' => 'status_id',
        'format' => 'html',
        'value' => function ($model) {
            $class = 'label-success' ;
            switch($model->status_id){
                case $model::STATUS_ACTIVE:{
                    $class = 'label-success';
                } break;
                case $model::STATUS_MODER:{
                    $class = 'label-warning';
                } break;
                default:{
                    $class = 'label-danger';
                } break;
            }

            return '<span class="label ' . $class . '">' . $model->status . '</span>';
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $statusArray,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => '---'],
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
        'class' => 'yii\grid\ActionColumn',
        'template' => '<nobr>{update}{delete}</nobr>',
        'buttons' => [
            'update' => function($url, $model) {
                return Html::a('<span class="icon fa fa-edit"></span>', $url, [
                    'class' => 'btn btn-sm btn-primary'
                ]);
            },
            'delete' => function($url, $model) {
                return Html::a('<span class="icon fa fa-trash"></span>', $url, [
                    'class' => 'btn btn-sm btn-danger isDel',
                    'data-method' => 'post',
                    'data-pjax' => 0,
                ]);
            },
        ]
    ],
];

$dynaGridOptions = [
    'columns' => $columns,
    'gridOptions' => [
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'after' => '<div class="pull-right">{pager}</div>',
        ],
    ],
    'options' => ['id' => 'dynagrid-comments'],
];

if (class_exists('\stepancher\adminlteTheme\config\AnminLteThemeConfig')) {
    DynaGrid::begin(\yii\helpers\ArrayHelper::merge(\stepancher\adminlteTheme\config\AnminLteThemeConfig::getDefaultConfigDynagrid(), $dynaGridOptions));
} else {
    DynaGrid::begin([
        'columns' => $columns,
        'toggleButtonGrid' => [
            'label' => '<i class="glyphicon glyphicon-wrench"></i> &nbsp&nbspНастройки',
        ],
        'toggleButtonFilter' => [
            'label' => '<i class="glyphicon glyphicon-filter"></i> &nbsp&nbsp Фильтры',
        ],
        'toggleButtonSort' => [
            'label' => '<i class="glyphicon glyphicon-sort"></i> &nbsp&nbsp Сортировка',
        ],
        'storage' => DynaGrid::TYPE_DB,
        'gridOptions' => [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'toolbar' => [
                [
                    'content' => Html::a('<i class="glyphicon glyphicon-repeat"></i> Сбросить', [''], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => 'Обновить'])
                ], [
                    'content' => '{dynagridFilter}{dynagridSort}{dynagrid}{toggleData}',
                ],
                '{export}',
            ],
            'export' => [
                'label' => 'Экспорт'
            ],
            'panel' => [
                'after' => false,
            ],
        ],
        'options' => ['id' => 'dynagrid-comments'],
    ]);
}
DynaGrid::end();
?>