<html>
<head>
    <script type="text/javascript" src="/js/jsAddress.js"></script>
</head>

<body>
<form action="" method="post">
<input type="text" name="name" value="<?=$model->name?>" ><br/>
<input type="text" name="name" value="<?=$model->detail_address?>" ><br/>
<input type="text" name="name" value="<?=$model->tel?>" ><br/>
<select id="cmbProvince" name="province" value="<?=$model->province?>"></select>
<select id="cmbCity" name="city"></select>
<select id="cmbArea" name="area"></select>
    <input type="hidden" name="_csrf-frontend" value="<?=Yii::$app->request->csrfToken?>"/>
    <input type="submit">
</form>
<script type="text/javascript">
    addressInit('cmbProvince', 'cmbCity', 'cmbArea');
</script>
</body>
</html>