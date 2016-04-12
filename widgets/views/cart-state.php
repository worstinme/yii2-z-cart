<?php 

use yii\helpers\Html;

if (count($cart->items)): ?>
<i class="uk-icon-shopping-basket uk-margin-right"> <span><?=$cart->amount?></span></i><?=Yii::$app->formatter->asCurrency($cart->sum)?>
<?php else: ?>
<i class="uk-icon-shopping-basket uk-margin-right"></i>Корзина
<?php endif;