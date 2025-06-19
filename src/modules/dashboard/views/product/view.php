<?php

use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Product $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="product-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены что хотите удалить запись?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'description:ntext',
            'price',
            'stock',
            'status',
            [
                'attribute' => 'status',
                'value' => $model::getStatusList()[$model->status] ?? $model->status,
            ],
            [
                'attribute' => 'category_id',
                'label' => 'Категория',
                'value' => $model->category->name ?? '(неизвестно)',
            ],
            [
                'attribute' => 'shop_id',
                'label' => 'Питомник',
                'value' => $model->shop->name ?? '(неизвестно)',
            ],
            [
                'attribute' => 'user_id',
                'label' => 'Пользователь',
                'value' => $model->user->username ?? '(неизвестно)',
            ],
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
