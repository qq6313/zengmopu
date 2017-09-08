 <table class="table table-bordered table-responsive">
        <a href="<?=\yii\helpers\Url::to(['article/add'])?>" class="btn btn-link">添加</a>
        <tr>
            <th>ID</th>
            <th>name</th>
            <th>intro</th>
            <th>sort</th>
            <th>content</th>
            <th>status</th>
            <th>create_time</th>
            <th>操作</th>
        </tr>
        <?php foreach ($models as $article):
//        var_dump($article->author->name);die;
            ?>
            <tr>
                <td><?=$article->id?></td>
                <td><?=$article->name?></td>
                <td><?=$article->intro?></td>
                <td><?=$article->sort?></td>
                <td><?=$article->article_detail->content?></td>
                <td>  <?php if($article->status==1){
                        echo '正常';
                    }elseif($article->status==0){echo '隐藏';}
                    else{echo '已删除';}
                    ?></td>
                <td><?=date('Y-m-d',$article->sort)?></td>
                <td> <a href="<?=\yii\helpers\Url::to(['article/edit','id'=>$article->id])?>" class="btn btn-link"> <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                    <a href="javascript:;" onclick="del()" class="btn btn-link"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
            </tr>
            <script type="text/javascript">

                function del() {

                    $.getJSON(
                        '/brand/delete?id='+<?=$article->id?>
                    );

                }
            </script>
        <?php endforeach;?>
    </table>
<?php
//分页工具条
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager,
    'prevPageLabel'=>'上一页',
    'nextPageLabel'=>'下一页'
]);