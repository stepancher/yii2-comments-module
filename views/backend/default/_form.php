<?php

/**
 * Comment form view.
 *
 * @var \yii\base\View $this View
 * @var \yii\widgets\ActiveForm $form Form
 * @var \stepancher\comments\models\backend\Comment $model Model
 * @var array $statusArray Statuses array
 */

use stepancher\comments\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<?php $form = ActiveForm::begin(); ?>

    <div class="box">
        <div class="box-body">
            <div class="row">
                <div class="col-sm-6">
                    <?= $form->field($model, 'status_id')->dropDownList($statusArray, ['prompt' => Module::t('comments', 'BACKEND_PROMPT_STATUS')]) ?>
                </div>
                <div class="col-sm-6">
                    <?= $form->field($model, 'content')->textarea() ?>
                </div>
            </div>
        </div>

        <div class="box-footer text-left">
            <?= Html::submitButton(
                $model->isNewRecord ? Module::t('comments', 'BACKEND_CREATE_SUBMIT') : Module::t('comments', 'BACKEND_UPDATE_SUBMIT'),
                [
                    'class' => 'btn btn-primary'
                ]
            ) ?>
        </div>
    </div>

<?php ActiveForm::end(); ?>