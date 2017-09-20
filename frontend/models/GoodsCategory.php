<?php
namespace frontend\models;

use yii\db\ActiveRecord;

class GoodsCategory extends ActiveRecord{
    public static function getDepth(){
      return GoodsCategory::find()->asArray()->all();
    }

}