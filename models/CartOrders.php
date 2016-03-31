<?php

namespace worstinme\zcart\models;

use Yii;
use yii\helpers\Json;

class CartOrders extends \yii\db\ActiveRecord
{
    
    public $jsonParams = ['adress','body','contactName','email','phone'];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cart_orders}}';
    }

    public function behaviors()
    {
        return [
            \yii\behaviors\TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['params'], 'required'],
            [['state', 'paid'], 'integer'],
            [['params'], 'string'],

            [$this->jsonParams,'string'],
            ['email','email'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'state' => 'State',
            'adress' => 'Адрес',
            'body' => 'Сообщение',
            'contactName' => 'Контактное лиц',
            'email' => 'Электронная почта',
            'phone' => 'Телефон',
            'paid' => 'Paid',
        ];
    }

    public function getItems() {
        return $this->hasMany(CartOrderItems::className(),['order_id'=>'id']);
    }

    public function getSum() {
        return CartOrderItems::find()->where(['order_id'=>$this->id])->sum('price*count'); 
    }
    
    public function __get($name)
    { 
        if (in_array($name, $this->jsonParams)) {
            return $this->getJsonParams($name);
        } else {
            return parent::__get($name);
        }
    }

    public function __set($name, $value)
    { 
        if (in_array($name, $this->jsonParams)) {
            return $this->setJsonParams($name, $value);
        } else {
            return parent::__set($name, $value);
        }
    } 

    public function getJsonParams($name) {
        $params = !empty($this->params) ? Json::decode($this->params) : [];
        return isset($params[$name]) ? $params[$name] : null;
    }

    public function setJsonParams($name,$value) {
        $params = !empty($this->params) ? Json::decode($this->params) : [];
        $params[$name] = $value;
        return $this->params = Json::encode($params);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if (!Yii::$app->user->isGuest) {
                $this->user_id = Yii::$app->user->identity->id;
            }
            
            return true;
        } else {
            return false;
        }
    }

}
