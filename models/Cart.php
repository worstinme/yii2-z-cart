<?php

namespace worstinme\zcart\models;

use Yii;
use yii\base\Model;

class Cart extends Model
{
    public $items = [];

    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['comment', 'phone','adress'], 'required'],
            [['name', 'phone','adress','body'], 'string'],
            // email has to be a valid email address
            ['email', 'email'],
        ];
    }

    public function init() {

        parent::init();

        foreach (Yii::$app->request->cookies->getValue('cart',[]) as $item)  $this->add($item);

    }

    public function add($data) {

        $item = new CartOrderItems;

        $item->setAttributes($data);

        if ($item->validate()) {
       
            foreach ($this->items as $it) {

                if ($it->item_id == $item->item_id && $it->relation == $item->relation) {
                    $it->count += $item->count;
                    return true;
                }
            }

            $this->items[] = $item;
            return true;
        }

        return false;

    }



    public function save()
    {
        $items = [];

        foreach ($this->items as $key=> $item) {
            if ($item->count > 0) {
                $items[] = $item->attributes;
            }
            else {
                unset($this->items[$key]);
            }
        } 

        return Yii::$app->response->cookies->add(new \yii\web\Cookie(['name'=>'cart','value'=>$items]));
    }

    public function getSum() {
        $sum = 0;
        foreach ($this->items as $item) $sum += $item->price*$item->count;
        return round($sum,2);
    }

    public function getAmount() {
        $amount = 0;
        foreach ($this->items as $item) $amount += $item->count;
        return $amount;
    }


    public function attributeLabels()
    {
        return [];
    }

    public function contact($order,$items)
    {
        if ($this->validate()) {

            $mailer = Yii::$app->mailer->compose('checkout', ['order' => $order,'items'=>$items,'form'=>$this])
                    ->setFrom(['benvenuto@studio-good.ru' => 'benvenuto.su'])
                    ->setTo([Yii::$app->params['adminEmail'],'benvenuto@studio-good.ru'])
                    ->setSubject('Заказ с сайта benvenuto.su');

            if ($mailer->send()) {
                return true;  
            }

        }
        return false;
    }
}
