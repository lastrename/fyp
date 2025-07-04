<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Shop $model */

$this->title = 'Редкатирование: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Питомники', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="shop-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
