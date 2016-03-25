<?php

namespace worstinme\zcart\models;

use Yii;

/**
 * This is the model class for table "{{%cart_orders}}".
 *
 * @property integer $id
 * @property string $user_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $state
 * @property integer $params
 * @property integer $paid
 */
class CartOrders extends \yii\db\ActiveRecord
{
   
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cart_orders}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'params'], 'required'],
            [['created_at', 'updated_at', 'state', 'params', 'paid'], 'integer'],
            [['user_id'], 'string', 'max' => 255],
            [['items'],'safe'],
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
            'params' => 'Params',
            'paid' => 'Paid',
        ];
    }
    

}
