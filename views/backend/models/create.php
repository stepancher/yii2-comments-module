<?php

/**
 * Comment model create view.
 *
 * @var \yii\base\View $this View
 * @var \stepancher\comments\models\backend\Model $model Model
 * @var array $statusArray Statuses array
 */

use stepancher\comments\Module;

$this->title = Module::t('comments-models', 'BACKEND_CREATE_TITLE');
$this->params['subtitle'] = Module::t('comments-models', 'BACKEND_CREATE_SUBTITLE');
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        'url' => ['index'],
    ],
    $this->params['subtitle']
];
?>
<?= $this->render(
    '_form',
    [
        'model' => $model,
        'statusArray' => $statusArray
    ]
);?>