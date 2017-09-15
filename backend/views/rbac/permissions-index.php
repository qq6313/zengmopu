
    <table class="table table-bordered table-responsive" id="table_id_example">

        <a href="<?=\yii\helpers\Url::to(['rbac/add-permission'])?>" class="btn btn-link">添加</a>
        <tr>
            <th>权限名称</th>
            <th>权限描述</th>
            <th>操作</th>
        </tr>
        <?php foreach ($permissions as $permission):
            ?>
            <tr data-id="<?=$permission->name?>">
                <td><?=$permission->name?></td>
                <td><?=$permission->description?></td>
                <td> <a href="<?=\yii\helpers\Url::to(['rbac/edit-permission','name'=>$permission->name])?>" class="btn btn-link"> <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                    <a href="javascript:;"  class="btn btn-link del_btn"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
            </tr>
        <?php endforeach;?>
    </table>

<?php

/**
 * @var $this \yii\web\View
 */
$del_url=\yii\helpers\Url::to(['rbac/delete-permission']);

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
      var name=tr.attr('data-id');
      $.post("{$del_url}",{name:name},function(data) {
      
        if(data=='success'){
            tr.hide('slow');
        }else{
            alert('删除失败');
        }
      })
  }
});
  $(document).ready( function () {
        $('#table_id_example').DataTable();
    } );
JS


));