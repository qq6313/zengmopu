
<table id="table_id_example" class="display">
    <thead>
        <a href="<?=\yii\helpers\Url::to(['rbac/add-permission'])?>" class="btn btn-link">添加</a>
        <tr>
            <th>权限名称</th>
            <th>权限描述</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($permissions as $permission):
            ?>
            <tr data-id="<?=$permission->name?>">
                <td><?=$permission->name?></td>
                <td><?=$permission->description?></td>
                <td> <a href="<?=\yii\helpers\Url::to(['rbac/edit-permission','name'=>$permission->name])?>" class="btn btn-link"> <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                    <a href="javascript:;"  class="btn btn-link del_btn"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
            </tr>
    </tbody>
        <?php endforeach;?>
    </table>

<?php

/**
 * @var $this \yii\web\View
 */
$del_url=\yii\helpers\Url::to(['rbac/delete-permission']);
$this->registerCssFile('http://cdn.datatables.net/1.10.15/css/jquery.dataTables.css');
$this->registerJsFile('http://code.jquery.com/jquery-1.10.2.min.js',['depends'=>\yii\web\JqueryAsset::className()]);
$this->registerJsFile('http://cdn.datatables.net/1.10.15/js/jquery.dataTables.js',['depends'=>\yii\web\JqueryAsset::className()]);
//注册js代码
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
      $(document).ready( function () {
        $('#table_id_example').DataTable();
    } );
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