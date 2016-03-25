<?php

namespace worstinme\zcart\widgets;

use Yii;

class BuyButton extends \yii\base\Widget
{

    public $model = null;

    public $options;

    public $label = 'Заказать';

    public function init()
    {
        parent::init();
    }

    public function run() {
        
        return $this->render('to-order',[
            'model'=> $this->model,
            'label'=>$this->label,
        ]);
    }
}