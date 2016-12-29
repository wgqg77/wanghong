<?php
/**
 * Created by PhpStorm.
 * User: wq
 * Date: 2016/12/27
 * Time: 下午3:00
 */
namespace app\modules\frontend\models;

use yii\db\ActiveRecord;
use yii\base\Security;
use Yii;

class User extends ActiveRecord {
    public $rememberMe = true;
    private $secretkey = 'ssapword2016';
    const SCENARIO_LOGIN = 'login';
    const SCENARIO_REGISTER = 'register';

    public static function tableName()
    {
        return 'user';
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_LOGIN] = ['username', 'password'];
        $scenarios[self::SCENARIO_REGISTER] = ['username', 'password', 'email', 'idcard'];
        return $scenarios;
    }

    public function rules()
    {
        return [
            [['username','password'],'required'],
            ['username', 'number'],
            ['username', 'string', 'min' => 11],
            ['username', 'filter', 'filter' => 'trim'],
            ['username','unique','message'=>'手机号被占用','on'=>'create'],
            ['username', 'match', 'pattern'=>'/^1[0~9]{10}$/', 'message'=>'格式错误'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'uid' => '用户id',
            'username' => '用户名',
            'password' => '密码',
            'create_time' => '创建时间',
            'create_ip' => '创建IP',
            'status' => '状态',
            'idcard' => '身份证号',
            'login_count' => '登录次数',
        ];
    }

    public function login() {
        if ($this->validate()) {
            $user = $this->findUserByUsername($this->username);
            if ($user) {
                if ($user->password == md5($this->password)) {
                    //注册后审核通过可以看？？？
                    if (1 == $user->status) {
                        Yii::$app->session['uid'] = $user->uid;
                        Yii::$app->session['username'] = $user->username;
                        $user->login_count++;
                        $user->save();
                        $userKey = Yii::$app->session->getId();
                        $userValue = $user->user_name."|".$user->uid;
                        $key = "session_id";
                        if (true == $this->rememberMe) {
                            $weekTime = time() + 3600 * 24 * 7;
                            setcookie($key,$userKey,$weekTime);
                            Yii::$app->cache->set($userKey, $userValue, $weekTime);
                        } else {
                            setcookie($key,$userKey);
                            Yii::$app->cache->set($userKey, $userValue);
                        }
                    } else {
                        $this->addError('账户未激活');
                    }
                } else {
                    return $this->addError('用户名或密码错误！');//密码
                }
            } else {
                return $this->addError('用户名或密码错误！');//用户名
            }
        }
    }

    public function regist() {
        if ($this->validate()) {
            $user = $this->findByUsername($this->username);
            if ($user) {
                return $this->addError('手机号已注册');
            } else {
                $idcard = $this->findIdcard();
                if ($idcard) {
                    return $this->addError('身份证号已被使用');
                } else {
                    $model = new User();
                    $model->username = $this->username;
                    $model->password = $this->password;
                    $model->idcard = Yii::$app->getSecurity()->encryptByPassword($idcard, $this->secretkey);
                    $model->save();
                }
            }
        }
    }

    public function findUserByUsername($username) {
        return $this->find()->where(['username' => $username])->one();
    }

    public function findIdcard($idcard) {
        $enId = Yii::$app->getSecurity()->encryptByPassword($idcard, $this->secretkey);
        return $this->find()->where(['idcard' => $enId])->one();
    }
}