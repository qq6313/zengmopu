<?php
use yii\web\JsExpression;

$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();

//echo $form->field($model,'intro')->widget('kucha\ueditor\UEditor',[]);
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
         $('#goods-logo').val(data.fileUrl);
         $('#logo').attr('src',data.fileUrl);
    }
}
EOF
        ),
    ]
]);
//图片结束

echo \yii\bootstrap\Html::img($model->logo,['id'=>'logo']);
echo $form->field($model, 'goods_category_id')->dropDownList(\yii\helpers\ArrayHelper::map($goodscategory,'id', 'name'));
echo $form->field($model, 'brand_id')->dropDownList(\yii\helpers\ArrayHelper::map($brand_id,'id', 'name'));
echo $form->field($model,'market_price')->textInput();
echo $form->field($model,'shop_price')->textInput();
echo $form->field($model,'stock')->textInput();
echo $form->field($model, 'is_on_sale',['inline'=>true])->radioList(['0'=>'下架','1'=>'在售']);
echo $form->field($model, 'status',['inline'=>true])->radioList(['0'=>'回收站','1'=>'正常']);
echo $form->field($model,'sort')->textInput();
echo $form->field($model1,'content')->widget('kucha\ueditor\UEditor',[]);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();