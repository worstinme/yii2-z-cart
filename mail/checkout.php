<?php

use yii\helpers\Html;

?>
<table style="width:100%">
<thead>
	<tr>
		<td style="border-bottom: 1px solid #ccc">Наименование</td>
		<td style="border-bottom: 1px solid #ccc">Количество</td>
		<td  style="border-bottom: 1px solid #ccc">Сумма</td>
	</tr>
</thead> 
<tbody>
<?php foreach ($order->items as $item): ?>
	<tr>
		<td style="border-bottom: 1px solid #ccc"><?= $item->model->name; ?></td>
		<td style="border-bottom: 1px solid #ccc"><?=$item->count?></td>
		<td style="border-bottom: 1px solid #ccc"><b><?=Yii::$app->formatter->asCurrency($item->sum)?></b></td>
	</tr>
<?php endforeach ?>
</tbody>
<tfoot>
	<tr>
		<td style="border-bottom: 1px solid #ccc">Итого:</td>
		<td style="border-bottom: 1px solid #ccc"></td>
		<td style="border-bottom: 1px solid #ccc"><b><?=Yii::$app->formatter->asCurrency($order->sum)?></b></td>
	</tr>
</tfoot>
</table>

<ul>
	<?php foreach ($order->jsonParams as $param): ?>
		<?php if (!empty($order->{$param})): ?>
			<li><strong><?=$order->getAttributeLabel($param)?></strong>: <?=$order->{$param}?>
		<?php endif ?>
	<?php endforeach ?>
</ul>
 
<p>Если Вы не оформляли заказ на нашем сайте, то просто удалите это письмо.</p>