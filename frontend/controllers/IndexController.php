<?php

namespace frontend\controllers;

use frontend\models\GoodsCategory;

class IndexController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->renderPartial('index');
    }

}
