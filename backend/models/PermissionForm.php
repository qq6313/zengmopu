<?php
namespace backend\models;
use yii\base\Model;

class PermissionForm extends Model{
    public $name;
    public $description;
    const SCENARIO_ADD='add';
    public function rules(){
        return [
         [['name','description'],'required'],
          ['name','validataName','on'=>self::SCENARIO_ADD]
        ];
    }

    public function validataName(){
        if(\Yii::$app->authManager->getPermission($this->name)){
            $this->addError('name','权限已存在');
        }
    }

    public function attributeLabels(){
        return [
          'name'=>'权限名称',
            'description'=>'描述',
        ];
    }
}