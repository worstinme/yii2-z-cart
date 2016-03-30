<?php

namespace worstinme\zcart\controllers;

use worstinme\zcart\models\Cart;
use yii\web\NotFoundHttpException;
use Yii;

class CartController extends \yii\web\Controller
{
    
    public $checkoutAccess = ['@'];
    public $orderModel = '\worstinme\zcart\models\CartOrders';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['checkout'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => $this->checkoutAccess,
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

        $order = $this->orderModel;

        if (count($cart->sum < Yii::$app->params['z-cart']['min_to_order'])) {
            
        }

        $model = new $order();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', 'Ваш заказ успешно отправлен.');
            return $this->redirect(['orders']);
        } 

        return $this->render('checkout',[
            'cart'=>$cart,
            'model'=>$model,
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

