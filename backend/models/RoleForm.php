<?php
namespace backend\models;
use yii\base\Model;

class RoleForm extends Model{
    public $name;
    public $description;
    public $permissions;
    public function rules(){
        return [
            [['name','description'],'required',],
            ['permissions','safe']
        ];
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