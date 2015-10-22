<?php

/**
 * Comment model form view.
 *
 * @var \yii\base\View $this View
 * @var \yii\widgets\ActiveForm $form Form
 * @var \stepancher\comments\models\backend\Model $model Model
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
                    <?= $form->field($model, 'name')->textInput(['placeholder' => Module::t('comments-models', 'BACKEND_CREATE_PLACEHOLDER_NAME')]) ?>
                </div>
                <div class="col-sm-6"></div>
            </div>
        </div>

        <div class="box-footer text-left">
            <?= Html::submitButton(
                $model->isNewRecord ? Module::t('comments-models', 'BACKEND_CREATE_SUBMIT') : Module::t('comments-models', 'BACKEND_UPDATE_SUBMIT'),
                [
                    'class' => 'btn btn-primary'
                ]
            ) ?>
        </div>
    </div>

<?php ActiveForm::end(); ?>