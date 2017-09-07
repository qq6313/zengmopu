<?php
/**
 * Created by PhpStorm.
 * User: brandistrator
 * Date: 2017/9/2
 * Time: 14:40
 */
?>
    <table class="table table-bordered table-responsive">
        <a href="<?=\yii\helpers\Url::to(['brand/add'])?>" class="btn btn-link">添加</a>
        <tr>
            <th>ID</th>
            <th>name</th>
            <th>intro</th>
            <th>logo</th>
            <th>sort</th>
            <th>status</th>
            <th>操作</th>
        </tr>
        <?php foreach ($models as $brand):
//        var_dump($brand->author->name);die;
            ?>
            <tr>
                <td><?=$brand->id?></td>
                <td><?=$brand->name?></td>
                <td><?=$brand->intro?></td>

                <td>
                    <?php if($brand->logo){
                       echo '<img src="';echo $brand->logo;echo '" width="50">';
                    }else{
                        echo '<img src="/upload/1.jpg" width="50">';
                    }?>
                </td>
                <td><?=$brand->sort?></td>
                <td>  <?php if($brand->status==1){
                        echo '正常';
                    }elseif($brand->status==0){echo '隐藏';}
                    else{echo '已删除';}
                    ?></td>
                <td> <a href="<?=\yii\helpers\Url::to(['brand/edit','id'=>$brand->id])?>" class="btn btn-link"> <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                    <a href="<?=\yii\helpers\Url::to(['brand/delete','id'=>$brand->id])?>" class="btn btn-link"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
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