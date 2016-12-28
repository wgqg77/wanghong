<?php
/**
 * Created by PhpStorm.
 * User: wq
 * Date: 2016/12/26
 * Time: 下午3:32
 */
namespace app\modules\backend;
use yii\base\Module;

class backend extends Module {
    public $controllerNamespace = 'app\modules\backend\controllers';
    public $defaultRoute = 'index';
    public $layout = 'main';

    public function init() {
        parent::init();
    }
}