<?php

/** @var \yii\web\View $this */
/** @var \yii\widgets\ActiveForm $form */
/** @var \ivankff\metaTags\models\MetaTag $model */

echo $form->field($model, 'title')->textarea(['rows' => 2]);
echo $form->field($model, 'keywords')->textarea(['rows' => 2]);
echo $form->field($model, 'description')->textarea(['rows' => 2]);
