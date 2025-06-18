<?php

use app\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Shop $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="shop-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

    <?php //$form->field($model, 'logo_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'owner_id')->dropDownList(
        ArrayHelper::map(User::find()->all(), 'id', 'username'),
        ['prompt' => 'Выберите владельца']
    ) ?>

    <?= $form->field($model, 'status')->dropDownList(
        $model::getStatusList(),
        ['prompt' => 'Выберите статус']
    ) ?>

    <?= $form->field($model, 'is_approved')->checkbox() ?>

    <?= $form->field($model, 'is_published')->checkbox() ?>

    <div class="form-group mt-4">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
