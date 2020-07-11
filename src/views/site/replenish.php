<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\forms\TransferForm */

$this->title = 'Replenish ' . $model->to;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Replenish ' . $model->to;
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_transferForm', [
        'model' => $model,
    ]) ?>

</div>
