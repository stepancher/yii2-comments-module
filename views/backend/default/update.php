<?php

/**
 * Comment update view.
 *
 * @var \yii\base\View $this View
 * @var \stepancher\comments\models\backend\Comment $model Model
 * @var array $statusArray Status array
 */

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
<?= $this->render(
    '_form',
    [
        'model' => $model,
        'statusArray' => $statusArray
    ]
);?>