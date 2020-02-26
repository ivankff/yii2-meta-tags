<?php

/** @var \yii\web\View $this */
/** @var \yii\widgets\ActiveForm $form */
/** @var \ivankff\models\MetaTag $model */

$form->field($model, 'title')->textarea(['rows' => 2]);
$form->field($model, 'keywords')->textarea(['rows' => 2]);
$form->field($model, 'description')->textarea(['rows' => 2]);
