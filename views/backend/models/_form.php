<?php

/**
 * Comment model form view.
 *
 * @var \yii\base\View $this View
 * @var \yii\widgets\ActiveForm $form Form
 * @var \stepancher\comments\models\backend\Model $model Model
 * @var \stepancher\themes\admin\widgets\Box $box Box widget instance
 * @var array $statusArray Statuses array
 */

use stepancher\comments\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<?php $form = ActiveForm::begin(); ?>
<?php $form = ActiveForm::begin(['options' => ['class' => 'panel form-horizontal']]); ?>
    <div class="panel-body no-padding-b">
        <div class="col-sm-12">
        <?= $form->field($model, 'name')->textInput(['placeholder' => Module::t('comments-models', 'BACKEND_CREATE_PLACEHOLDER_NAME')]) ?>
        <?= Html::submitButton(
            $model->isNewRecord ? Module::t('comments-models', 'BACKEND_CREATE_SUBMIT') : Module::t('comments-models', 'BACKEND_UPDATE_SUBMIT'),
            [
                'class' => 'btn btn-primary'
            ]
        ) ?>

        </div>
    </div>

<?php ActiveForm::end(); ?>