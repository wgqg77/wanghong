<?php
/**
 * Created by PhpStorm.
 * User: wq
 * Date: 2016/12/26
 * Time: 下午3:35
 */
namespace app\modules\frontend;
use yii\base\Module;

class frontend extends Module {
    public $controllerNamespace = 'app\modules\frontend\controllers';
    public $layout = '@app/modules/backend/views/layouts/main';
    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
    }
}