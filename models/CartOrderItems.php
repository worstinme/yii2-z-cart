<?php

namespace worstinme\zcart\models;

use Yii;

/**
 * This is the model class for table "{{%cart_order_items}}".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $item_id
 * @property string $model
 * @property double $price
 * @property integer $count
 */
class CartOrderItems extends \yii\db\ActiveRecord
{
    private $model;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cart_order_items}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id', 'relation', 'count'], 'required'],
            [['item_id','relation'], 'integer'],
            [['price'], 'number'],
            [['count'],'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'item_id' => 'Item ID',
            'relation' => 'relation',
            'price' => 'Price',
            'count' => 'Count',
        ];
    }


    public function getModel() {
        if($this->model === null && !empty(Yii::$app->params['z-cart']['relations'][$this->relation])) {
            $class = Yii::$app->params['z-cart']['relations'][$this->relation];
            $this->model = $class::findOne($this->item_id);
        }
        return $this->model;
    }

    public function getSum() {
        return round($this->count*$this->price,2);
    }
}
