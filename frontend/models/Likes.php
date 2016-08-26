<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "likes".
 *
 * @property integer $id
 * @property integer $news_id
 * @property string  $user
 */
class Likes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'likes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['news_id'], 'integer'],
            [['user'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'      => 'ID',
            'news_id' => 'News ID',
            'user'    => 'User',
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
     * Получение датапровайдера лайков
     *
     * @param array $params Параметры запроса
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Likes::find();

        $dataProvider = new ActiveDataProvider(['query' => $query]);

        $this->load($params);

        if(!$this->validate())
        {
            return $dataProvider;
        }

        $query->andFilterWhere(['id' => $this->id]);

        $query->andFilterWhere(['news_id' => $this->news_id])
            ->andFilterWhere(['like', 'user', $this->user]);

        return $dataProvider;
    }
}
