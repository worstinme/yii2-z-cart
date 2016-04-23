<?php

namespace worstinme\zcart\widgets;

use Yii;
use worstinme\zcart\models\Cart;

class CartState extends \yii\base\Widget
{

    public $cart;

    public $options;

    public $title = 'Корзина';
    public $title_a = '';
    public $title_b = '';

    public function init()
    {
        parent::init();
    }

    public function run() {
        
        $cart = $this->cart === null ? new Cart : $this->cart ;

        return $this->render('cart-state',[
            'title'=>$this->title,
            'title_a'=>$this->title_a,
            'title_b'=>$this->title_b,
            'cart'=>$cart,
        ]);
    }
}