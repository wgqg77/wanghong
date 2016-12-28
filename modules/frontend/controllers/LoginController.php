<?php
/**
 * Created by PhpStorm.
 * User: wq
 * Date: 2016/12/27
 * Time: 下午2:58
 */

namespace app\modules\frontend\controllers;

use app\modules\frontend\models\User;
use yii\web\Controller;
use Yii;

class LoginController extends Controller {

    public function ActionLogin() {
        $key = "session_id";
        $userKey = isset($_COOKIE[$key]) ? $_COOKIE[$key] : null;
        if ($userKey) {
            $userValue = Yii::$app->cache->get($userKey);
            if ($userValue) {
                return $this->redirect(array('/frontend/index/index'));
            }
        }
        $user = new User();
        if ($user->load(Yii::$app->request->post())){
            if ($user->login()) {
                $this->redirect(array('/frontend/index/index'));
            } else {
                $error = array_keys($user->errors[0]);
                return $this->renderPartial('login', [
                    'user' => $user,
                    'error' => $error
                ]);
            }
        } else {
            return $this->renderPartial('login', [
                'user' => $user,
                'error' => ''
            ]);
        }
    }

    public function ActionLoginout() {
        Yii::$app->getSession()->destroy();
        $cook = isset($_COOKIE['session_id']) ? $_COOKIE['session_id'] : null ;
        if($cook){
            Yii::$app->cache->set($cook,null);
        }
        setcookie("session_id",null);
        $this->redirect(array('login/login'));
    }

    public function ActionRegist() {}
}