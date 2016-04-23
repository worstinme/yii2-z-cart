<?php 

use yii\helpers\Html;

if (count($cart->items)): ?>
<i class="uk-icon-shopping-basket uk-margin-right"> <sup class="amount"><?=$cart->amount?></sup></i><?=Yii::$app->formatter->asCurrency($cart->sum)?>
<?php else: ?>
<i class="uk-icon-shopping-basket uk-margin-right"></i><?=$title?>
<?php endif;