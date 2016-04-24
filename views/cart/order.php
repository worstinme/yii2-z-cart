<?php

use yii\helpers\Html;
use worstinme\uikit\Breadcrumbs;

/* @var $this yii\web\View */
/* @var $model worstinme\zcart\models\CartOrders */

$this->title = 'Заказ №' . $model->id;

$this->params['breadcrumbs'][] = ['label'=>'Ваш заказ','url'=>['index']];
$this->params['breadcrumbs'][] = ['label'=>'История заказов','url'=>['orders']];'';
$this->params['breadcrumbs'][] = $this->title;


?>

<div class="cart cart-orders">

	<div class="breadcrumbs">
		<?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]) ?>
	</div>

	<table class="uk-table uk-form uk-table-condensed uk-table-striped uk-table-hover uk-table-bordered">
		<thead>
			<tr>
				<th>Наименование</th>
				<td class="uk-text-center">Статус</td>
				<td class="uk-text-center">Количество</td>
				<td class="uk-text-right">Сумма</td>
			</tr>
		</thead> 
		<tbody>
		<?php foreach ($model->items as $item): ?>
			<tr>
				<td><?= Html::a($item->model->name, $item->model->url, ['target' => '_blank','data'=>['pjax'=>false]]); ?></td>
				<td class="uk-text-center"><?=!empty($model::$states[$model->state]) ? $model::$states[$model->state] : $model->state?></td>
				<td class="uk-text-center count"><?=$item->count?></td>
				<td class="uk-text-right price"><b><?=Yii::$app->formatter->asCurrency($item->sum)?></b></td>
			</tr>
		<?php endforeach ?>
		</tbody>
		<?php if (count($model->items) > 1): ?>
			
		<tfoot>
			<tr>
				<td class="uk-text-right" colspan="2">Итого:</td>
				<td class="uk-text-center"><?=$model->amount?></td>
				<td class="uk-text-right"><b style="font-style: normal;"><?=Yii::$app->formatter->asCurrency($model->sum)?></b></td>
			</tr>
		</tfoot>

		<?php endif ?>
	</table>

	<ul class="uk-list uk-list-striped">
	<?php foreach ($model->jsonParams as $param): ?>
		<?php if (!empty($model->{$param})): ?>
			<li><strong><?=$model->getAttributeLabel($param)?></strong>: <?=$model->{$param}?>
		<?php endif ?>
	<?php endforeach ?>
	</ul>

</div>