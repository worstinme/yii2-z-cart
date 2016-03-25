<?php

namespace worstinme\zcart\controllers;

use worstinme\zcart\models\Cart;
use yii\web\NotFoundHttpException;
use Yii;

class CartController extends \yii\web\Controller
{
  
    public $relations = [];

    public function actionIndex() {

        $cart = new Cart;

        if (Yii::$app->request->isPost) {
            if ($cart->add(Yii::$app->request->post())) {
                $cart->save();
            }
        }

        return $this->render('index',[
        	'cart'=>$cart,
        ]);
    }

    public function actionToOrder() {

        $cart = new Cart;

        $success = false;

        if (Yii::$app->request->isPost) {
            if ($cart->add(Yii::$app->request->post())) {
                $cart->save();
                $success = true;
            }
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON; 

        return [
            'items'=>$cart->items,
            'success'=>$success,
            'status'=>$success?'success':'warning',
            'message'=>$success?'Добавлено! '.\yii\helpers\Html::a('Перейти в корзину.',['index']):'Не удалось обновить корзину',
            'sended'=>Yii::$app->request->post(),
        ];

    }

    public function getViewPath()
    {
        return Yii::getAlias('@worstinme/zcart/views/cart');
    }
}

