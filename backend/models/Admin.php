<?php

namespace backend\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "admin".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $last_login_time
 * @property string $last_login_ip
 */
class Admin extends \yii\db\ActiveRecord  implements IdentityInterface
{
    public $password;
    public $old_password;
    public $repassword;

    public $name;
    public $description;
    public $roles;

    const SCENARIO_ADD='add';
    const SCENARIO_EDIT='edit';
    const SCENARIO_OLD='old';
    /**
     * @inheritdoc
     */

    public static function tableName()
    {
        return 'admin';
    }

    public function beforeSave($insert)
    {
        if($insert){
            $this->password_hash= \Yii::$app->security->generatePasswordHash($this->password);
            $this->created_at=time();

            $this->auth_key=\Yii::$app->security->generateRandomString();
        }else{
            $this->updated_at=time();
            if($this->password){
                $this->password_hash= \Yii::$app->security->generatePasswordHash($this->password);
                $this->auth_key=\Yii::$app->security->generateRandomString();
            }
        }
        return parent::beforeSave($insert);
    }

    public static function getMenus()
    {
        $menuItems = [];
        $menus = Menu::find()->where(['parent_menu' => 0])->all();
        foreach ($menus as $menu) {
            $children = Menu::find()->where(['parent_menu' => $menu->id])->all();
            $items = [];
            foreach ($children as $child) {
                if(Yii::$app->user->can($child->address)) {
                    $items[] = ['label' => $child->name, 'url' => [$child->address]];
                }
            }
            $menuItems[] = ['label' => $menu->name, 'items' => $items];
        }
        return $menuItems;
    }

    public static function getPermissionItems(){
        $permissions=\Yii::$app->authManager->getRoles();
        $items=[];
        foreach ($permissions as $permission){
            $items[$permission->name]=$permission->description;
        }
        return $items;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username',  'email'], 'required','on'=>self::SCENARIO_EDIT],
            [['repassword'],'compare','compareAttribute'=>'password'],
           [ ['old_password'],'required','on'=>self::SCENARIO_OLD],
            [['password'],'required','on'=>self::SCENARIO_ADD],
            [['username', 'password', 'email'], 'string', 'max' => 255],
            [['username'], 'unique'],
            [['email'], 'unique'],
            ['roles','safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'password' => '密码',
            'old_password'=>'请输入您的旧密码',
            'repassword'=>'确认新密码'
        ];
    }

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return self::findOne(['id'=>$id]);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
       return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
       return $authKey=$this->auth_key;
    }
}
