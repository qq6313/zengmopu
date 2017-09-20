<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property string $name
 * @property string $parent_menu
 * @property string $address
 * @property integer $sort
 * @property integer $menu
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_menu'], 'required'],
            [['sort'], 'integer'],
            [['name', 'parent_menu', 'address'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'parent_menu' => '上级菜单',
            'address' => '地址/路由',
            'sort' => '排序',
            'menu' => 'Menu',
        ];
    }
}
