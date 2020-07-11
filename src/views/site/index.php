<?php

/* @var $this yii\web\View */

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $searchModel app\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'My Yii Application';
?>
<div class="site-index">



    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'username',
            [
                'attribute' => 'balance',
                'value' => function ($model) {
                    return Yii::$app->formatter->asDecimal($model->balance, 2);
                },
                'filter' => false
            ],
            [
                'attribute' => 'created_at',
                'value' => function ($model) {
                    return Yii::$app->formatter->asDate($model->created_at, 'php:Y-m-d Y H:i:s');
                },
                'filter' => false
            ],
            [
                'attribute' => 'modified_at',
                'value' => function ($model) {
                    return Yii::$app->formatter->asDate($model->modified_at, 'php:Y-m-d H:i:s');
                },
                'filter' => false
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '<div class="btn-group btn-group-flex btn-group-xs" role="group">{replenish} {withdraw}</div>',
                'visibleButtons' => [
                    'replenish' => function ($model, $key, $index) {
                        return $model->id !== Yii::$app->user->id;
                    },
                    'withdraw' => function ($model, $key, $index) {
                        return $model->id !== Yii::$app->user->id;
                    }
                ],
                'buttons' => [
                    'replenish' => function ($url, $model) {
                        $attributes = [
                            'title' => 'Replenish',
                            'class' => 'btn btn-info btn-ico',
                            'data-pjax' => '0',
                        ];
                        if (Yii::$app->user->isGuest) {
                            $attributes['disabled'] = '';
                        }
                        return Html::a(
                            '<i class="glyphicon glyphicon-plus-sign"></i>',
                            $url,
                            $attributes
                        );
                    },
                    'withdraw' => function ($url, $model) {
                        $attributes = [
                            'title' => 'Withdraw',
                            'class' => 'btn btn-primary btn-ico',
                            'data-pjax' => '0'
                        ];
                        if (Yii::$app->user->isGuest) {
                            $attributes['disabled'] = '';
                        }
                        return Html::a(
                            '<i class="glyphicon glyphicon-minus-sign"></i>',
                            $url,
                            $attributes
                        );
                    }
                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
