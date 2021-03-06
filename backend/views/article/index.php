 <table class="table table-bordered table-responsive">
        <a href="<?=\yii\helpers\Url::to(['article/add'])?>" class="btn btn-link">添加</a>
        <tr>
            <th>ID</th>
            <th>name</th>
            <th>article_category</th>
            <th>intro</th>
            <th>sort</th>
            <th>status</th>
            <th>create_time</th>
            <th>操作</th>
        </tr>
        <?php foreach ($models as $article):
//        var_dump($article->author->name);die;
            ?>
            <tr data-id="<?=$article->id?>">
                <td><?=$article->id?></td>
                <td><?=$article->name?></td>
                <td><?=$article->articleCategory->name?></td>
                <td><?=$article->intro?></td>
                <td><?=$article->sort?></td>
                <td>  <?php if($article->status==1){
                        echo '正常';
                    }elseif($article->status==0){echo '隐藏';}
                    else{echo '已删除';}
                    ?></td>
                <td><?=date('Y-m-d',$article->create_time)?></td>
                <td> <a href="<?=\yii\helpers\Url::to(['article/edit','id'=>$article->id])?>" class="btn btn-link"> <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                    <a href="<?=\yii\helpers\Url::to(['article/show','id'=>$article->id])?>" class="btn btn-link"> <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                    <a href="javascript:;" class="btn btn-link del_btn"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
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
$del_url=\yii\helpers\Url::to(['article/delete']);

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