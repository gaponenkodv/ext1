<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ReturnForm;

/**
 * ReturnFormSearch represents the model behind the search form about `app\models\ReturnForm`.
 */
class ReturnFormSearch extends ReturnForm
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id'], 'integer'],
            [['body', 'datetime'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
        $query = ReturnForm::find();

        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
            ]
        );

        $this->load($params);

        if(!$this->validate())
        {
            return $dataProvider;
        }

        $query->andFilterWhere(
            [
                'id'       => $this->id,
                'user_id'  => $this->user_id,
                'datetime' => $this->datetime,
            ]
        );

        $query->andFilterWhere(['like', 'body', $this->body]);

        return $dataProvider;
    }
}
