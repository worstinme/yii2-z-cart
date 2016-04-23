<?php 

use yii\helpers\Html;
use yii\helpers\Url;
use worstinme\uikit\Breadcrumbs;

$this->registerJs('$.pjax.defaults.scrollTo = false', $this::POS_READY);
$this->title = 'Ваш заказ';

$this->params['breadcrumbs'][] = $this->title; 

\yii\widgets\Pjax::begin(['id'=>'z-cart','timeout'=>5000,'options'=>['data-uk-observe'=>true,'scrollTo'=>false,'class'=>'cart']]); ?> 

	<?php if ($this->context->breadcrumbs): ?>
		<div class="breadcrumbs">
			<?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]) ?>
		</div>	
	<?php endif ?>

	<h1 class="uk-text-center"><span><?=$this->title?></span></h1>

	<?php if (count($cart->items) <= 0): ?>
		<div class="uk-text-center empty-cart"><?=$cart->emptyCartText?></div>
	<?php else: ?>

	<table class="z-cart uk-table uk-table-hover uk-table-condensed">
	<thead>
		<tr>
			<th>Наименование</th>
			<th class="uk-text-center" colspan="3">Количество</th>
			<th class="uk-text-right">Сумма</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($cart->items as $item): ?>
		<?=$this->render('_row',['cart'=>$cart,'item'=>$item])?>
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

	<?php if ($cart->sum >= $cart->minToOrder): ?>
		
		<p class="uk-text-center"><?= Html::a('Оформить', ['checkout'], ['class' => 'uk-button','data'=>['pjax'=>false]]); ?></p>

	<?php else: ?>

		<p class="uk-text-center">Минимальная сумма заказа: <?=$cart->minToOrder?> <i class="uk-icon-rub"></i></p>

	<?php endif ?>


	<?php endif ?>

	<p class="uk-text-center"><?= Html::a('<i class="uk-icon-list-alt uk-margin-right"></i>История заказов', ['orders']); ?></p>

	<?php $js = <<<JS

$("input[name='count']").on("keyup change",function(e){
    $(this).next(".z-cart-save").removeClass("uk-hidden");
});

$("body").on("mouseenter keypress click",".z-cart-save",function(e){
    var params = $(this).data("params");
    var input = $(this).prev("input[name='count']");
    params.count = input.val() - Number(input.data('count')); 
    $(this).data("params",params);
    console.log(params);
});

JS;

$this->registerJs($js, $this::POS_READY); ?>

<?php  \yii\widgets\Pjax::end();