<?php

use app\models\Shop;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ShopSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Питомники';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить питомник', ['create'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'name',
            'description:ntext',
            //'slug',
            //'logo_id',
            [
                'attribute' => 'owner_id',
                'value' => fn($model) => $model->owner->username ?? '(неизвестно)',
                'filter' => $searchModel->getUserList(),
            ],
            [
                'attribute' => 'status',
                'value' => fn($model) => $model::getStatusList()[$model->status] ?? $model->status,
                'filter' => $searchModel::getStatusList(),
            ],
            'is_approved:boolean',
            'is_published:boolean',
            //'created_at',
            //'updated_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Shop $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
