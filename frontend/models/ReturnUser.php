<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "return_user".
 *
 * @property integer $id
 * @property integer $email
 * @property string $name
 *
 * @property ReturnForm[] $returnForms
 */
class ReturnUser extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'return_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'name'], 'required'],
            ['email', 'email'],
            [['name'], 'string', 'max' => 80],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReturnForms()
    {
        return $this->hasMany(ReturnForm::className(), ['user_id' => 'id']);
    }
}
