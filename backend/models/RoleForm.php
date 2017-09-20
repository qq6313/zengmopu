<?php
namespace backend\models;
use yii\base\Model;

class RoleForm extends Model{
    public $name;
    public $description;
    public $permissions;
    const SCENARIO_ROLE='add';
    public function rules(){
        return [
            [['name','description'],'required',],
            ['permissions','safe'],
            ['name','validataName','on'=>self::SCENARIO_ROLE]
        ];
    }
    public function validataName(){
        if(\Yii::$app->authManager->getRole($this->name)){
            $this->addError('name','角色已存在');
        }
    }

    public static function getPermissionItems(){
        $permissions=\Yii::$app->authManager->getPermissions();
        $items=[];
        foreach ($permissions as $permission){
            $items[$permission->name]=$permission->description;
        }
        return $items;
    }

}