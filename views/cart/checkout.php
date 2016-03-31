<?php 

use yii\helpers\Html;
use yii\helpers\Url;
use worstinme\uikit\Breadcrumbs;
use worstinme\uikit\ActiveForm;

$this->registerJs('$.pjax.defaults.scrollTo = false', $this::POS_READY);
$this->title = 'Оформление заказа';

$this->params['breadcrumbs'][] = ['label'=>'Ваш заказ','url'=>['index']];
$this->params['breadcrumbs'][] = 'Оформление';

?>

<div class="uk-container uk-container-center">
	<?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]) ?>
</div>


<div class="main">
<div class="uk-container uk-container-center">

<?php  \yii\widgets\Pjax::begin(['id'=>'z-cart','timeout'=>5000,'options'=>['data-uk-observe'=>true,'scrollTo'=>false]]); ?> 

	<h1 class="uk-text-center"><span><?=$this->title?></span></h1>

	<?php $form = ActiveForm::begin(['id' => 'contact-form','layout'=>'horizontal','field_width'=>'full','options'=>['class'=>'uk-margin-large-top']]); ?>

    	<?= $form->field($model, 'contactName') ?>

    	<?= $form->field($model, 'email') ?>

        <?= $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::className(), [
            'mask' => '+7 (999) 9999999',
        ]) ?>

        <?= $form->field($model, 'adress') ?>

        <?= $form->field($model, 'body')->textArea(['rows' => 6,'placeholder'=>'Оставьте комментарий к заказу']) ?>

		<div class="uk-form-row uk-text-center">
            <?= Html::submitButton('Оформить', ['class' => 'uk-button uk-button-danger', 'name' => 'contact-button']) ?>
        </div>

    <?php ActiveForm::end(); ?>

<?php  \yii\widgets\Pjax::end(); ?>


</div>
</div>