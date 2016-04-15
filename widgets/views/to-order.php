<?php 

use yii\helpers\Html;
use yii\helpers\Url;

\worstinme\uikit\assets\Notify::register($this);

?>
<?php if ($model !== null) : ?>

<div class="z-cart-widget">

<i class="uk-icon-minus" data-minus></i> <?= Html::textInput('count', null,['size'=>1,'required'=>true,'placeholder'=>'0']); ?> <i class="uk-icon-plus" data-plus></i>

<?= Html::a($label, $url = null, ['class'=> 'buy-button', 
    	'data'=> [
    		'item_id'=>$model->id, 
    		'relation'=>0, 
    		'price'=>$model->price
    	]
    ]); ?>

</div>

<?php endif; ?>

<?php $url = yii\helpers\Url::toRoute(['/cart/to-order']);

$js = <<<JS

$(".z-cart-widget [data-minus]").on("click",function(e){
    var input = $(this).parent("div").find("input");
    input.val(Number(input.val()) - 1);
    e.preventDefault();
});

$(".z-cart-widget [data-plus]").on("click",function(e){
    var input = $(this).parent("div").find("input");
    input.val(Number(input.val()) + 1);
    e.preventDefault();
});

$(".buy-button").on("click", function(e) {
	var count = $(this).parents(".z-cart-widget").find("[name='count']").val(), 
        relation = $(this).data("relation"), 
        price = $(this).data("price"), 
        item_id = $(this).data("item_id");

	$.ajax({
        url: '$url',
        type: 'POST',
        data: {
            count: count,
            relation: relation,
            item_id: item_id,
            price: price,
            _csrf: yii.getCsrfToken()
        },
        success: function(data) {
            UIkit.notify({message:data.message,status:data.message});
            $(".z-cart-state").html(data.state),
            console.log(data);
        }
    });

    e.preventDefault();

});

JS;

$this->registerJs($js, $this::POS_READY);