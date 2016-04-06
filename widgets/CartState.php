<?php

namespace worstinme\zcart\widgets;

use Yii;
use worstinme\zcart\models\Cart;

class CartState extends \yii\base\Widget
{

    public $cart;

    public $options;

    public $label = 'Заказать';

    public function init()
    {
        parent::init();
    }

    public function run() {
        
        $cart = $this->cart === null ? new Cart : $this->cart ;

        return $this->render('cart-state',[
            'label'=>$this->label,
            'cart'=>$cart,
        ]);
    }
}