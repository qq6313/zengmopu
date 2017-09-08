<?php
use yii\web\JsExpression;

$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'intro')->widget('kucha\ueditor\UEditor',[]);
echo $form->field($model,'logo')->hiddenInput();



//外部TAG 图片开始
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
echo \flyok666\uploadifive\Uploadifive::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'formData'=>['someKey' => 'someValue'],
        'width' => 120,
        'height' => 40,
        'onError' => new JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadComplete' => new JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        console.log(data.fileUrl);
         $('#brand-logo').val(data.fileUrl);
         $('#logo').attr('src',data.fileUrl);
    }
}
EOF
        ),
    ]
]);
//图片结束

echo \yii\bootstrap\Html::img($model->logo,['id'=>'logo']);
echo $form->field($model,'sort')->textInput();

echo $form->field($model, 'status',['inline'=>true])->radioList(['0'=>'隐藏','1'=>'正常']);

echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();