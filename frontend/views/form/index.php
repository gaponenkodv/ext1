<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \app\models\ReturnForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ReturnFormSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Return Forms';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="return-form-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Return Form', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            [
                'attribute'=>'user_id',
                'label'=>'Пользователь',
                'format'=>'text',
                'value'=>'user.email',
                'filter' => ReturnForm::getEmailList()
            ],
            'body:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
