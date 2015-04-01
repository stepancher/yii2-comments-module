<?php

/**
 * Comment update view.
 *
 * @var \yii\base\View $this View
 * @var \stepancher\comments\models\backend\Comment $model Model
 * @var \backend\themes\admin\widgets\Box $box Box widget instance
 * @var array $statusArray Status array
 */

use stepancher\themes\admin\widgets\Box;
use stepancher\comments\Module;

$this->title = Module::t('comments', 'BACKEND_UPDATE_TITLE');
$this->params['subtitle'] = Module::t('comments', 'BACKEND_UPDATE_SUBTITLE');
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