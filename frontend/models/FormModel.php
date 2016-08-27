<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 26.08.16
 * Time: 21:35
 */

namespace frontend\models;

use yii\base\Model;
use Yii;

class FormModel extends Model
{
    public $email;
    public $body;
    public $name;
    public $verifyCode;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'body', 'name'], 'required'],
            ['email', 'email'],
            [['body'], 'string', 'max' => 4000],
            [['name'], 'string', 'max' => 80],
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'Id',
            'name'       => 'Имя пользователя',
            'body'       => 'Сообщение',
            'datetime'   => 'Время добавления',
            'email'      => 'Email',
            'verifyCode' => 'Верификация',
        ];
    }

    /**
     * Отправка сообщения
     *
     * @param string $email Email
     * @param integer $userId Id пользователя
     *
     * @return bool
     */
    public function sendEmail($email, $userId)
    {
        $body = $this->body .
            "\n<a href=http://test.local/form/index?ReturnFormSearch[user_id]={$userId}>Перейти к просмотру</a>";

        return Yii::$app->mailer->compose()
            ->setTo([$email, 'info@awardwallet.com'])
            ->setFrom([$this->email => $this->name])
            ->setSubject('Оставлен отзыв')
            ->setTextBody($this->body)
            ->send();
    }

}