<?php

use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Shop $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Питомники', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="shop-view">

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
            'slug',
            //'logo_id',
            [
                'attribute' => 'owner_id',
                'value' => $model->owner->username ?? '(неизвестно)',
            ],
            [
                'attribute' => 'status',
                'value' => $model::getStatusList()[$model->status] ?? $model->status,
            ],
            'is_approved:boolean',
            'is_published:boolean',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
