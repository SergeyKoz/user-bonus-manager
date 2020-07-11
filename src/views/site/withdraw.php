<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\forms\TransferForm */

$this->title = 'Withdraw ' . $model->from;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Withdraw ' . $model->from;
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_transferForm', [
        'model' => $model,
    ]) ?>

</div>
