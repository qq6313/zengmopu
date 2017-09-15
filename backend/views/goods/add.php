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

echo $form->field($model,'goods_category_id')->hiddenInput();
echo '<div><ul id="treeDemo" class="ztree"></ul></div>';

$this->registerCssFile('@web/ztree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/ztree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);
$goodsCategories = json_encode(\backend\models\Goods::getZtree());
$this->registerJs(new \yii\web\JsExpression(
        <<<JS
 var setting = {
            data: {
                simpleData: {
                    enable: true,
                    idKey: "id",
                    pIdKey: "parent_id",
                    rootPId: 0
                }
            },
            callback: {//事件回调函数
		        onClick: function(event, treeId, treeNode){
		            
		             //获取当前点击节点的id,写入parent_id的值
		             $("#goods-goods_category_id").val(treeNode.id);
		        }
	        }
        };
        // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
        var zNodes = {$goodsCategories};
        
        zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
        //展开全部节点
        zTreeObj.expandAll(true);
        //修改 根据当前分类的parent_id来选中节点
        //获取你需要选中的节点 
        var node = zTreeObj.getNodeByParam("id", "{$model->goods_category_id}", null);
        zTreeObj.selectNode(node);
JS
    )
);

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