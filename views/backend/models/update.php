<?php

/**
 * Comment model update view.
 *
 * @var \yii\base\View $this View
 * @var \stepancher\comments\models\backend\Model $model Model
 * @var \stepancher\themes\admin\widgets\Box $box Box widget instance
 * @var array $statusArray Statuses array
 */

use stepancher\comments\Module;
use stepancher\themes\admin\widgets\Box;

$this->title = Module::t('comments-models', 'BACKEND_UPDATE_TITLE');
$this->params['subtitle'] = Module::t('comments-models', 'BACKEND_UPDATE_SUBTITLE');
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        'url' => ['index'],
    ],
    $this->params['subtitle']
];
?>
<div class="box">
    <div class="row">
        <div class="col-sm-4">
            <?= $this->render(
                '_form',
                [
                    'model' => $model,
                    'statusArray' => $statusArray
                ]
            );?>

        </div>
    </div>
</div>