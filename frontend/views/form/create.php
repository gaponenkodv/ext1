<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ReturnForm */

$this->title = 'Create Return Form';
$this->params['breadcrumbs'][] = ['label' => 'Return Forms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="return-form-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
