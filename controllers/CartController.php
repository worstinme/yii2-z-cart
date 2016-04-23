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
    public $relations = [];
    public $breadcrumbs = false;

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

    public function actionOrder($id)
    {
        $model = $this->findModel($id);

        return $this->render('order', [
            'model' => $model,
        ]);        
    }

    public function actionCheckout() {

        $cart = new Cart;

        $order = $this->orderModel;

        if ($cart->sum < $cart->minToOrder) {
            return $this->redirect(['index']);
        }

        $model = new $order();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            foreach ($cart->items as $item) {
                $item->order_id = $model->id;
                $item->save();
            }

            $cart->close();

            $from = !empty(Yii::$app->params['senderEmail']) ? Yii::$app->params['senderEmail'] : Yii::$app->params['adminEmail'];
            
            Yii::$app->mailer->compose('@worstinme/zcart/mail/checkout', ['order' => $model])
                        ->setFrom($from)
                        ->setTo(Yii::$app->params['adminEmail'])
                        ->setSubject($model->adminEmailSubject)
                        ->send();

            if (!empty($model->email)) {

                $mailer = Yii::$app->mailer->compose('@worstinme/zcart/mail/checkout', ['order' => $model])
                    ->setFrom($from)->setTo($model->email)->setSubject($model->emailSubject);

                if ($mailer->send()) {
                    Yii::$app->getSession()->setFlash('success', 'Спасибо! Ваш заказ отправлен.');
                }

            }

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
            'message'=>$success?'Добавлено! '.\yii\helpers\Html::a('Перейти в корзину',['index']).'.':'Не удалось обновить корзину',
            'sended'=>Yii::$app->request->post(),
            'state'=>\worstinme\zcart\widgets\CartState::widget(['cart'=>$cart]),
        ];

    }

    protected function findModel($id)
    {   
        $order = $this->orderModel;
        
        if (($model = $order::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function getViewPath()
    {
        return Yii::getAlias('@worstinme/zcart/views/cart');
    }
}

