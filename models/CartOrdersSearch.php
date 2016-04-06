<?php

namespace worstinme\zcart\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use worstinme\zcart\models\CartOrders;

/**
 * CartOrdersSearch represents the model behind the search form about `worstinme\zcart\models\CartOrders`.
 */
class CartOrdersSearch extends CartOrders
{
    /**
     * @inheritdoc
     */

    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['user.username']);
    }

    public function rules()
    {
        return [
            [['id', 'user_id', 'created_at', 'updated_at', 'state', 'paid'], 'integer'],
            [['params'], 'safe'],
            [['user.username'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = CartOrders::find()->joinWith(['user']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=>[
                'defaultOrder'=>['updated_at' => SORT_DESC],   
            ], 
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'state' => $this->state,
            'paid' => $this->paid,
        ]);

        $query->andFilterWhere(['like', 'params', $this->params]);
        $query->andFilterWhere(['like', 'user.username', $this->getAttribute('user.username')]);

        return $dataProvider;
    }
}
