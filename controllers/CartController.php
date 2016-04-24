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

        $allow = Yii::$app->request->cookies->getValue('report-allow',false);

        if ($allow) {
            $dataProvider = new ActiveDataProvider([
                'query' => $order::find()->orderBY('id DESC'),
            ]);
        }
        else {

            if (Yii::$app->user->isGuest) {
                return $this->goHome();
            }

            $dataProvider = new ActiveDataProvider([
                'query' => $order::find()->where(['user_id'=>Yii::$app->user->identity->id])->orderBY('id DESC')
            ]);
        }

        return $this->render('orders',[
            'dataProvider'=>$dataProvider,
        ]);
    }

    public function actionReport() {

        if (Yii::$app->has('zoo')) $secret = Yii::$app->zoo->config('cart_report_secret',false);

        if (!isset($secret) || $secret === false) {
            return $this->goHome();
        }

        $allow = Yii::$app->request->cookies->getValue('report-allow',false);

        if (Yii::$app->request->isPost) {
            if (Yii::$app->request->post('secret') == $secret) {
                Yii::$app->response->cookies->add(new \yii\web\Cookie(['name'=>'report-allow','value'=>true]));
                $allow = true;
            }
        }

        if (isset($allow) && $allow) {
            return $this->redirect(['orders']);
        }

        return $this->render('deny');
    }

    public function actionOrder($id)
    {
        $model = $this->findModel($id);

        $allow = Yii::$app->request->cookies->getValue('report-allow',false);

        if ($allow || (!Yii::$app->user->isGuest && Yii::$app->user->identity->id == $model->user_id)) {
            return $this->render('order', [
                'model' => $model,
            ]);
        }

        return $this->render('deny');        
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

            if (Yii::$app->has('zoo')) {
                $adminEmail = Yii::$app->zoo->config('admin_email',Yii::$app->params['adminEmail']);
                $from = Yii::$app->zoo->config('cart_sender_email',$adminEmail);
            }

            $secret = Yii::$app->zoo->config('cart_report_secret',false);
            
            Yii::$app->mailer->compose('@worstinme/zcart/mail/checkout', ['order' => $model])
                        ->setFrom($from)
                        ->setTo($adminEmail)
                        ->setSubject($model->adminEmailSubject)
                        ->send();

            if (!empty($model->email)) {

                $mailer = Yii::$app->mailer->compose('@worstinme/zcart/mail/checkout', ['order' => $model])
                    ->setFrom($from)->setTo($model->email)->setSubject($model->emailSubject);

                if ($mailer->send()) {
                    Yii::$app->getSession()->setFlash('success', 'Спасибо! Ваш заказ отправлен.');
                }

            }

            return $this->redirect(['finish']);

        } 

        return $this->render('checkout',[
            'cart'=>$cart,
            'model'=>$model,
        ]);
    }

    public function actionFinish() {
        return $this->render('finish');
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

