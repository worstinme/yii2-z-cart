<?php 

use yii\helpers\Html;
use yii\helpers\Url;
use worstinme\uikit\Breadcrumbs;

$this->registerJs('$.pjax.defaults.scrollTo = false', $this::POS_READY);
$this->title = 'Ваш заказ';

$this->params['breadcrumbs'][] = $this->title;

?>

<div class="uk-container uk-container-center">
	<?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]) ?>
</div>

<div class="main">
<div class="uk-container uk-container-center">

<?php  \yii\widgets\Pjax::begin(['id'=>'z-cart','timeout'=>5000,'options'=>['data-uk-observe'=>true,'scrollTo'=>false]]); ?> 

	<h1 class="uk-text-center"><span><?=$this->title?></span></h1>

	<?php if (count($cart->items) <= 0): ?>
	<?=Yii::$app->params['z-cart']['empty_cart_text']?>
	<?php else: ?>

	<table class="z-cart uk-table uk-table-hover uk-table-condensed">
	<thead>
		<tr>
			<th>Наименование</th>
			<td class="uk-text-center" colspan="3">Количество</td>
			<td class="uk-text-right">Сумма</td>
			<th></td>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($cart->items as $item): ?>
		<tr>
			<td><?= Html::a($item->model->name, $item->model->url, ['target' => '_blank','data'=>['pjax'=>false]]); ?></td>
			<td class="uk-text-right">
				<?php if ($item->count>1): ?>
				<?= Html::a('<i class="uk-icon-minus"></i>', ['index'], ['class'=>'z-cart-plus', 'data' => [ 
						'method'=>'post','pjax'=>true,
						'params' => [ 'item_id'=>$item->item_id, 'relation'=>$item->relation, 'count'=>-1, ],
				]]); ?>
				<?php endif ?>
			</td>
			<td class="uk-text-center count"><?=$item->count?></td>
			<td>
				<?= Html::a('<i class="uk-icon-plus"></i>', ['index'], ['class'=>'z-cart-plus', 'data' => [ 
						'method'=>'post','pjax'=>true,
						'params' => [ 'item_id'=>$item->item_id, 'relation'=>$item->relation, 'count'=>1, ],
				]]); ?>
			</td>
			<td class="uk-text-right price"><b><?=Yii::$app->formatter->asCurrency($item->sum)?></b></td>
			<td class="uk-text-center">
				<?= Html::a('<i class="uk-icon-times-circle-o"></i>', ['index'], 
					['data' => [ 
						'method'=>'post','pjax'=>true,
						'confirm'=>'Уверены что хотите убрать товар из заказа?',
						'params' => [ 'item_id'=>$item->item_id, 'relation'=>$item->relation, 'count'=>-($item->count), ],
					]
				]); ?>
			</td>
		</tr>
	<?php endforeach ?>
	</tbody>
	<tfoot>
		<tr>
			<td class="uk-text-right">Итого:</td>
			<td></td>
			<td class="uk-text-center"><?=$cart->amount?></td>
			<td></td>
			<td class="uk-text-right"><b style="font-style: normal;"><?=Yii::$app->formatter->asCurrency($cart->sum)?></b></td>
			<td></td>
		</tr>
	</tfoot>
	</table>

	<?php if ($cart->sum >= Yii::$app->params['z-cart']['min_to_order']): ?>
		
		<p class="uk-text-center"><?= Html::a('Оформить', ['checkout'], ['class' => 'tm-button-red','data'=>['pjax'=>false]]); ?></p>

	<?php else: ?>

		<p class="uk-text-center">Минимальная сумма заказа: <?=Yii::$app->params['z-cart']['min_to_order']?> <i class="uk-icon-rub"></i></p>

	<?php endif ?>


	<?php endif ?>

<?php  \yii\widgets\Pjax::end(); ?>


</div>
</div>