<?php

/**
 * Comment form view.
 *
 * @var \yii\base\View $this View
 * @var \yii\widgets\ActiveForm $form Form
 * @var \stepancher\comments\models\backend\Comment $model Model
 * @var \stepancher\themes\admin\widgets\Box $box Box widget instance
 * @var array $statusArray Statuses array
 */

use stepancher\comments\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<?php $form = ActiveForm::begin(); ?>
    <div class="panel-body no-padding-b">
        <div class="col-sm-12">
            <?= $form->field($model, 'status_id')->dropDownList($statusArray, ['prompt' => Module::t('comments', 'BACKEND_PROMPT_STATUS')]) ?>
            <?= $form->field($model, 'content')->textarea() ?>

        <?= Html::submitButton(
            $model->isNewRecord ? Module::t('comments', 'BACKEND_CREATE_SUBMIT') : Module::t('comments', 'BACKEND_UPDATE_SUBMIT'),
            [
                'class' => 'btn btn-primary'
            ]
        ) ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>