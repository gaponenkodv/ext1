<?php

namespace app\models;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "return_form".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $body
 * @property string $datetime
 *
 * @property ReturnUser $user
 */
class ReturnForm extends \yii\db\ActiveRecord
{
    public $id;
    public $email;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'return_form';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'body'], 'required'],
            [['user_id'], 'integer'],
            [['user'], 'safe'],
            [['body'], 'string', 'max' => 4000],
            ['datetime', 'DateRangeValidator'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => ReturnUser::className(), 'targetAttribute' => ['user_id' => 'id']],
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
            'body' => 'Body',
            'datetime' => 'Datetime',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(ReturnUser::className(), ['id' => 'user_id']);
    }

    /**
     * Получение списка аккаунтов для фильтра
     *
     * @return array
     */
    public static function getEmailList()
    {
        $users = ReturnForm::find()
            ->select(['user_id', 'email'])
            ->joinWith(['user'])
            ->distinct(true)
            ->all();

        return ArrayHelper::map($users, 'user_id', 'email');
    }

}
