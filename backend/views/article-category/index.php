<?php
/**
 * Created by PhpStorm.
 * User: article-categoryistrator
 * Date: 2017/9/2
 * Time: 14:40
 */
?>
    <table class="table table-bordered table-responsive">
        <a href="<?=\yii\helpers\Url::to(['article-category/add'])?>" class="btn btn-link">添加</a>
        <tr>
            <th>ID</th>
            <th>name</th>
            <th>intro</th>
            <th>sort</th>
            <th>status</th>
            <th>操作</th>
        </tr>
        <?php foreach ($models as $article_category):
//        var_dump($article-category->author->name);die;
            ?>
            <tr>
                <td><?=$article_category->id?></td>
                <td><?=$article_category->name?></td>
                <td><?=$article_category->intro?></td>
                <td><?=$article_category->sort?></td>
                <td>  <?php if($article_category->status==1){
                        echo '正常';
                    }elseif($article_category->status==0){echo '隐藏';}
                    else{echo '已删除';}
                    ?></td>
                <td> <a href="<?=\yii\helpers\Url::to(['article-category/edit','id'=>$article_category->id])?>" class="btn btn-link"> <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                    <a href="<?=\yii\helpers\Url::to(['article-category/delete','id'=>$article_category->id])?>" class="btn btn-link"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
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