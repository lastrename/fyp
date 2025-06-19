<?php

use app\models\Category;
use app\models\Shop;
use app\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Product $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'price')->textInput(['type' => 'number', 'step' => '0.01']) ?>

    <?= $form->field($model, 'stock')->textInput(['type' => 'number']) ?>

    <?= $form->field($model, 'status')->dropDownList(
        $model::getStatusList(),
        ['prompt' => 'Выберите статус']
    ) ?>


    <?= $form->field($model, 'category_id')->label('Категория')->dropDownList(
        Category::getList(),
        ['prompt' => 'Выберите категорию']
    ) ?>

    <?= $form->field($model, 'shop_id')->label('Питомник')->dropDownList(
        Shop::getList(),
        ['prompt' => 'Выберите питомник']
    ) ?>

    <?= $form->field($model, 'user_id')->label('Пользователь')->dropDownList(
        User::getList(),
        ['prompt' => 'Выберите пользователя']
    ) ?>

    <div class="form-group mt-4">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
