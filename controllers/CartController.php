<?php

namespace worstinme\zcart\controllers;

use worstinme\zcart\models\Cart;
use yii\web\NotFoundHttpException;
use Yii;

class CartController extends \yii\web\Controller
{
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['checkout'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'actions'=>['checkout'],
                    ],
                ],
            ],
        ];
    }

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

    public function actionCheckout() {

        $cart = new Cart;

        return $this->render('checkout',[
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
            'message'=>$success?'Добавлено! '.\yii\helpers\Html::a('<em>Перейти в корзину</em>',['index']).'.':'Не удалось обновить корзину',
            'sended'=>Yii::$app->request->post(),
        ];

    }

    public function getViewPath()
    {
        return Yii::getAlias('@worstinme/zcart/views/cart');
    }
}

