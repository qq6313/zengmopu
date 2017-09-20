<?php
/**
 * Created by PhpStorm.
 * User: menuistrator
 * Date: 2017/9/2
 * Time: 14:40
 */
?>
    <table class="table table-bordered table-responsive">
        <a href="<?=\yii\helpers\Url::to(['menu/add'])?>" class="btn btn-link">添加</a>
        <tr>
            <th>name</th>
            <th>address</th>
            <th>操作</th>
        </tr>
        <?php foreach ($models as $menu):
            ?>
            <tr data-id="<?=$menu->id?>">
                <td><?=$menu->name?></td>
                <td><?=$menu->address?></td>
                <td> <a href="<?=\yii\helpers\Url::to(['menu/edit','id'=>$menu->id])?>" class="btn btn-link"> <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
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
$del_url=\yii\helpers\Url::to(['menu/delete']);

//注册js代码
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
$('.del_btn').click(function() {
  
  if (confirm('确定要删除吗')){
      var tr=$(this).closest('tr');
      var id=tr.attr('data-id');

      $.post("{$del_url}",{id:id},function(data) {
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