<?php

use yii\helpers\Html;
use worstinme\uikit\ActiveForm;

/* @var $this yii\web\View */
/* @var $model worstinme\zcart\models\CartOrders */

$this->title = 'Заказ №' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Список заказов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="cart-orders-update uk-grid uk-grid-small">

	<div class="uk-width-medium-7-10">

		<table class="items z-cart uk-table uk-form uk-table-condensed uk-table-striped uk-table-hover uk-table-bordered">
			<thead>
				<tr>
					<th>Наименование</th>
					<td class="uk-text-center">Количество</td>
					<td class="uk-text-right">Сумма</td>
				</tr>
			</thead> 
			<tbody>
			<?php foreach ($model->items as $item): ?>
				<tr>
					<td><?= Html::a($item->model->name, $item->model->url, ['target' => '_blank','data'=>['pjax'=>false]]); ?></td>
					<td class="uk-text-center count"><?=$item->count?></td>
					<td class="uk-text-right price"><b><?=Yii::$app->formatter->asCurrency($item->sum)?></b></td>
				</tr>
			<?php endforeach ?>
			</tbody>
			<tfoot>
				<tr>
					<td class="uk-text-right">Итого:</td>
					<td class="uk-text-center"><?=$model->amount?></td>
					<td class="uk-text-right"><b style="font-style: normal;"><?=Yii::$app->formatter->asCurrency($model->sum)?></b></td>
				</tr>
			</tfoot>
		</table>

		<div class="uk-panel uk-panel-box" style="padding: 0">
			<ul class="uk-list uk-list-striped">
			<?php foreach ($model->jsonParams as $param): ?>
				<?php if (!empty($model->{$param})): ?>
					<li><strong><?=$model->getAttributeLabel($param)?></strong>: <?=$model->{$param}?>
				<?php endif ?>
			<?php endforeach ?>
			</ul>
		</div>

	</div>

	<?php $form = ActiveForm::begin(['id' => 'contact-form','layout'=>'stacked','field_width'=>'full','options'=>['class'=>'uk-width-medium-3-10']]); ?>

		<?= $form->field($model, 'state')->dropDownList($model::$states)->label(false); ?>

		<?= $form->field($model, 'paid')->dropDownList($model::$paids)->label(false); ?>

		<?= $form->field($model, 'body')->textArea(['rows' => 6,'placeholder'=>'Комментарий к заказу']) ?>

		<div class="uk-form-row">
	        <?= Html::submitButton('Сохранить', ['class' => 'uk-button uk-button-danger', 'name' => 'contact-button']) ?>
	    </div>

	<?php ActiveForm::end(); ?>

</div>
