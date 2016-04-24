<?php

use yii\helpers\Html;
use worstinme\uikit\ActiveForm;

 $form = ActiveForm::begin(['id' => 'contact-form','layout'=>'horizontal','field_width'=>'full','options'=>['class'=>'uk-margin-large-top']]); ?>

    	<div class="uk-form-row uk-text-center">
    		<?= Html::textInput('secret', $value = null, ['placeholder' => 'введите пароль']); ?>
    	</div>

		<div class="uk-form-row uk-text-center">
            <?= Html::submitButton('Оформить', ['class' => 'uk-button uk-button-danger', 'name' => 'contact-button']) ?>
        </div>

<?php ActiveForm::end(); ?>