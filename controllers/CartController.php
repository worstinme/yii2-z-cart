<?php

namespace worstinme\zcart\controllers;

use worstinme\zcart\models\Cart;
use worstinme\zcart\models\CartOrders;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use Yii;

class CartController extends \yii\web\Controller
{
    
    public $checkoutAccess = ['@'];
    public $orderModel = '\worstinme\zcart\models\CartOrders';
    public $states = [
        'Отправлен',
        'Выполнен',
    ];

    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['checkout','orders'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => $this->checkoutAccess,
                        'actions'=>['checkout','orders'],
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

    public function actionOrders() {

        $order = $this->orderModel;

        $dataProvider = new ActiveDataProvider([
            'query' => $order::find()->where(['user_id'=>Yii::$app->user->identity->id])
        ]);

        return $this->render('orders',[
            'dataProvider'=>$dataProvider,
        ]);
    }

    public function actionCheckout() {

        $cart = new Cart;

        $order = $this->orderModel;

        if ($cart->sum < Yii::$app->params['z-cart']['min_to_order']) {
            return $this->redirect(['index']);
        }

        $model = new $order();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            foreach ($cart->items as $item) {
                $item->order_id = $model->id;
                $item->save();
            }

            $cart->close();

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

