<?php 

use yii\helpers\Html;
use yii\helpers\Url;
use worstinme\uikit\Breadcrumbs;
use worstinme\uikit\ActiveForm;

$this->registerJs('$.pjax.defaults.scrollTo = false', $this::POS_READY);
$this->title = 'Заказ оформлен';

$this->params['breadcrumbs'][] = 'Заказ оформлен';

?>
<div class="cart">
<?php if ($this->context->breadcrumbs): ?>
	<div class="breadcrumbs">
			<?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]) ?>
	</div>	
<?php endif ?>

<h1 class="uk-text-center"><span><?=$this->title?></span></h1>

<div class="uk-text-center">
    <p>Ваш заказ принят.</p>
    <p><a href="/">Перейти на главную</a></p>
</div>
</div>