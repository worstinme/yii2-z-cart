<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel worstinme\zcart\models\CartOrdersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Список заказов';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cart-orders-index">


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'summaryOptions'=>['class'=>'uk-text-center'],
        'tableOptions'=> ['class' => 'uk-table uk-form uk-table-condensed uk-table-hover uk-table-bordered uk-margin-top'],
        'options'=> ['class' => 'items'],
        'layout' => '{items}{pager}',
        'pager' => ['class'=> 'worstinme\uikit\widgets\LinkPager'],
        'columns' => [
            [
                'attribute'=>'id',
                'label'=>'№',
                'contentOptions'=>['class'=>'uk-text-center'],    
                'headerOptions'=>['style'=>'width:20px;','class'=>'uk-text-center'],      
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{update}',
                'buttons'=>[
                    'update' => function ($url, $model) { return Html::a('<i class="uk-icon-edit"></i>', $url ); },
                ],
                'contentOptions'=>['class'=>'uk-text-center'],                           
            ],
            [
                'attribute'=>'sum',
                'label'=>'Сумма заказа',
                'value' => function ($model, $index, $widget) {
                    return Yii::$app->formatter->asCurrency($model->sum);
                },
                'contentOptions'=>['class'=>'uk-text-right'],    
                'headerOptions'=>['class'=>'uk-text-right'],      
            ],
            [
                'attribute'=>'state',
                'label'=>'Статус',
                'format' => 'raw',
                'filter'=>$searchModel::$states,
                'value' => function ($model, $index, $widget) {
                    return !empty($model::$states[$model->state]) ? $model::$states[$model->state] : $model->state;
                },
                'headerOptions'=>['class'=>'uk-text-center'],
                'contentOptions'=>['class'=>'uk-text-center'],
            ],
            [
                'attribute'=>'paid',
                'label'=>'Оплата',
                'format' => 'raw',
                'filter'=>$searchModel::$paids,
                'value' => function ($model, $index, $widget) {
                    return !empty($model::$paids[$model->paid]) ? $model::$paids[$model->paid] : $model->paid;
                },
                'headerOptions'=>['class'=>'uk-text-center'],
                'contentOptions'=>['class'=>'uk-text-center'],
            ],
            [
                'label'=>'Товаров',
                'value' => function ($model, $index, $widget) {
                    return count($model->items);
                },
                'headerOptions'=>['class'=>'uk-text-center'],
                'contentOptions'=>['class'=>'uk-text-center'],
            ],
            [
                'attribute'=>'updated_at',
                'label'=>'Дата',
                'value' => function ($model, $index, $widget) {
                    return Yii::$app->formatter->asDate($model->updated_at);
                },
                'filter'=>false,
                'contentOptions'=>['class'=>'uk-text-center'],    
                'headerOptions'=>['class'=>'uk-text-center'],      
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{delete}',
                'buttons'=>[
                    'delete' => function ($url, $model) {     
                      return Html::a('<i class="uk-icon-trash"></i>', $url, [
                              'title' => 'Удалить',
                              'data'=>[
                                  'method'=>'post',
                                  'confirm'=>'Точно удалить?',
                              ],
                      ]);                                
                    },
                    
                ],
                'contentOptions'=>['class'=>'uk-text-center'],                           
            ],
        ],
    ]); ?>
</div>
