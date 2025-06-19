<?php

use app\models\Category;
use app\models\Product;
use app\models\Shop;
use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ProductSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Товары';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать товар', ['create'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'name',
            //'description:ntext',
            'price',
            'stock',
            [
                'attribute' => 'status',
                'value' => fn($model) => $model::getStatusList()[$model->status] ?? $model->status,
                'filter' => $searchModel::getStatusList(),
            ],
            [
                'attribute' => 'category_id',
                'label' => 'Категория',
                'value' => fn($model) => $model->category->name ?? null,
                'filter' => Category::getList(),
            ],
            [
                'attribute' => 'shop_id',
                'label' => 'Питомник',
                'value' => fn($model) => $model->shop->name ?? null,
                'filter' => Shop::getList(),
            ],
            [
                'attribute' => 'user_id',
                'label' => 'Пользователь',
                'value' => fn($model) => $model->user->username ?? null,
                'filter' => User::getList(),
            ],
            //'created_at',
            //'updated_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Product $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
