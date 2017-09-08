<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'intro')->widget('kucha\ueditor\UEditor',[]);
echo $form->field($model,'sort')->textInput();
echo $form->field($model,'article_category_id')->hiddenInput();
echo $form->field($model, 'status',['inline'=>true])->radioList(['0'=>'隐藏','1'=>'正常']);
echo $form->field($model1,'content')->widget('kucha\ueditor\UEditor',[]);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();