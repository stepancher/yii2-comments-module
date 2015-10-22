<?php

/**
 * @var \yii\base\View $this View
 * @var \yii\data\ActiveDataProvider $dataProvider Data provider
 * @var \stepancher\comments\models\backend\CommentSearch $searchModel Search model
 * @var array $statusArray Statuses array
 */

use stepancher\comments\Module;
use kartik\grid\GridView;
use kartik\dynagrid\DynaGrid;

use yii\grid\ActionColumn;
use yii\helpers\Html;

$this->title = Module::t('comments-models', 'BACKEND_INDEX_TITLE');
$this->params['subtitle'] = Module::t('comments-models', 'BACKEND_INDEX_SUBTITLE');
$this->params['breadcrumbs'] = [
    $this->title
];

$columns = [
    ['class' => 'kartik\grid\SerialColumn', 'order' => DynaGrid::ORDER_FIX_LEFT],
    'id',
    'name',
    [
        'attribute' => 'status_id',
        'format' => 'html',
        'value' => function ($model) {
            $class = ($model->status_id === $model::STATUS_ENABLED) ? 'label-success' : 'label-danger';

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
                    'class' => 'btn btn-sm btn-danger isDel'
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
            'before' => Html::a('<i class="btn-label glyphicon fa fa-plus"></i> &nbsp&nbsp' . Module::t('comments-models','BACKEND_CREATE_SUBTITLE'), ['create'], ['class' => 'btn btn-labeled btn-success no-margin-t']),
            'after' => '<div class="pull-right">{pager}</div>',
        ],
    ],
    'options' => ['id' => 'dynagrid-comments-models'],
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
                'before' => Html::a('<i class="btn-label glyphicon fa fa-plus"></i> &nbsp&nbsp' . Module::t('comments-models','BACKEND_CREATE_SUBTITLE'), ['create'], ['class' => 'btn btn-labeled btn-success no-margin-t']),
                'after' => false,
            ],
        ],
        'options' => ['id' => 'dynagrid-comments-models'],
    ]);
}
DynaGrid::end();
?>