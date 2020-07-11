<?php

use app\models\forms\TransferForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\forms\TransferForm */
/* @var $form yii\widgets\ActiveForm */

$fromOptions = [];
$toOptions = [];
if ($model->scenario == TransferForm::SCENARIO_REPLENISH) {
    $fromOptions['readonly'] = true;
}

if ($model->scenario == TransferForm::SCENARIO_WITHDRAW) {
    $toOptions['readonly'] = true;
}
?>
<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'from')->textInput($fromOptions) ?>

    <?= $form->field($model, 'to')->textInput($toOptions) ?>

    <?= $form->field($model, 'sum')->textInput() ?>

    <?= $form->field($model, 'description')->textarea() ?>

    <div class="form-group">
        <?= Html::submitButton('Transfer', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
