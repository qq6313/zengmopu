<?php
$form = \yii\bootstrap\ActiveForm::begin([
    'method' => 'get',
    'action'=>\yii\helpers\Url::to(['goods/index']),
    'options'=>['class'=>'form-inline']
]);
echo $form->field($model,'name')->textInput(['placeholder'=>'商品名'])->label(false);
echo $form->field($model,'sn')->textInput(['placeholder'=>'货号'])->label(false);
echo $form->field($model,'minPrice')->textInput(['placeholder'=>'最小值'])->label(false);
echo $form->field($model,'maxPrice')->textInput(['placeholder'=>'最大值'])->label('-');
echo \yii\bootstrap\Html::submitButton('搜索',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
?>
    <table class="table table-bordered table-responsive">
<!--
        <div class="row">
            <form method="post">
            <div class="col-md-3"><input type="text" name="name" class="form-control" placeholder="名称" width="20px"></div>
            <div class="col-md-3"><input type="text" name="lower" class="form-control" placeholder="最低售价" width="20px"></div>
            <div class="col-md-3"><input type="text" name="higher" class="form-control" placeholder="最高售价" width="20px"></div>

            <div class="col-md-1"><button type="submit" class="btn btn-info ">搜索</button></div>
            </form>
        </div>-->
        <a href="<?=\yii\helpers\Url::to(['goods/add'])?>" class="btn btn-link">添加</a>
        <tr>
            <th>ID</th>
            <th>名称</th>
            <th>货号</th>
            <th>图片</th>
            <th>商品分类</th>
            <th>品牌分类</th>
            <th>市场售价</th>
            <th>商品售价</th>
            <th>库存</th>
            <th>是否销售</th>
            <th>状态</th>
            <th>排序</th>
            <th>创建时间</th>
            <th>浏览次数</th>
            <th>操作</th>
        </tr>
        <?php foreach ($models as $goods):
//        var_dump($goods->author->name);die;
            ?>
            <tr data-id="<?=$goods->id?>">
                <td><?=$goods->id?></td>
                <td><?=$goods->name?></td>
                <td><?=$goods->sn?></td>
                <td>
                    <?php if($goods->logo){
                        echo '<img src="';echo $goods->logo;echo '" width="50">';
                    }else{
                        echo '<img src="/upload/1.jpg" width="50">';
                    }?>
                </td>
                <td><?=$goods->goodsCategory->name?></td>
                <td><?=$goods->brand->name?></td>
                <td><?=$goods->market_price?></td>
                <td><?=$goods->shop_price?></td>
                <td><?=$goods->stock?></td>
                <td><?php if($goods->is_on_sale){
                    echo '在售';
                    }else{
                    echo '下架';
                    }?></td>
                <td><?=$goods->sort?></td>
                <td>  <?php if($goods->status==1){
                        echo '正常';
                    }elseif($goods->status==0){echo '隐藏';}
                    else{echo '已删除';}
                    ?></td>
                <td><?=date('Y-m-d',$goods->create_time)?></td>
                <td><?=$goods->view_times?></td>
                <td> <a href="<?=\yii\helpers\Url::to(['goods/edit','id'=>$goods->id])?>" class="btn btn-link"> <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                 <a href="<?=\yii\helpers\Url::to(['goods/gallery','id'=>$goods->id])?>" class="btn btn-link"> <span class="glyphicon glyphicon-film" aria-hidden="true"></span></a>
                 <a href="<?=\yii\helpers\Url::to(['goods/show','id'=>$goods->id])?>" class="btn btn-link"> <span class="glyphicon glyphicon-search" aria-hidden="true"></span></a>
                    <a href="javascript:;"  class="btn btn-link del_btn"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
            </tr>
        <?php endforeach;?>
    </table>
<?php
//分页工具条
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager,
    'prevPageLabel'=>'上一页',
    'nextPageLabel'=>'下一页'
]);
/**
 * @var $this \yii\web\View
 */
$del_url=\yii\helpers\Url::to(['goods/delete']);

//注册js代码
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
/*$('.search').click(
    function() {
      alert(1);
    }
);*/
$('.del_btn').click(function() {
  if (confirm('确定要删除吗')){
      var tr=$(this).closest('tr');
      var id=tr.attr('data-id');

      $.post("{$del_url}",{id:id},function(data) {
          console.debug(data);
        if(data=='success'){
            tr.hide('slow');
        }else{
            alert('删除失败');
        }
      })
  }
})
JS


));