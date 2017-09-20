

<!--第二步：添加如下 HTML 代码-->
<table id="table_id_example" class="display">
    <thead>
    <tr>
        <th>Column 1</th>
        <th>Column 2</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Row 1 Data 1</td>
        <td>Row 1 Data 2</td>
    </tr>
    <tr>
        <td>Row 2 Data 1</td>
        <td>Row 2 Data 2</td>
    </tr>
    </tbody>
</table>
<?php
$this->registerCssFile('http://cdn.datatables.net/1.10.15/css/jquery.dataTables.css');
$this->registerJsFile('http://code.jquery.com/jquery-1.10.2.min.js',['depends'=>\yii\web\JqueryAsset::className()]);
$this->registerJsFile('http://cdn.datatables.net/1.10.15/js/jquery.dataTables.js',['depends'=>\yii\web\JqueryAsset::className()]);
$this->registerJs(new \yii\web\JsExpression(
    <<<JS

    $(document).ready( function () {
        $('#table_id_example').DataTable();
    } );


JS
));

