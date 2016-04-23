<?php

use yii\helpers\Html;

?>
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
	<td class="uk-text-center count">
		<?= Html::textInput('count', $item->count,['data'=>['count'=>$item->count]]); ?>
		<?= Html::a('<i class="uk-icon-check"></i>', ['index'], ['class'=>'z-cart-save  uk-hidden', 'data' => [ 
				'method'=>'post','pjax'=>true,
				'params' => [ 'item_id'=>$item->item_id, 'relation'=>$item->relation, 'count'=>0, ],
		]]); ?>
	</td>
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