<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "address".
 *
 * @property integer $id
 * @property string $name
 * @property string $city
 * @property string $detail_address
 */
class Address extends \yii\db\ActiveRecord
{


    public function getLocations(){
        return $this->hasOne(Locations::className(),['id'=>'id']);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'city','detail_address','tel','province','area'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '收货人',
            'city' => '所在城市',
            'detail_address' => '详细地址',
        ];
    }
}
