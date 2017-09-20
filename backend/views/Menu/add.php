<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'parent_menu')->dropDownList(\yii\helpers\ArrayHelper::map($menu,'id', 'name'));
echo $form->field($model,'address')->dropDownList(\yii\helpers\ArrayHelper::map($permissions,'name', 'name'));
echo $form->field($model,'sort')->textInput();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();