<?php
/**
 * Created by PhpStorm.
 * User: wq
 * Date: 2016/12/27
 * Time: 上午11:42
 */

namespace app\modules\backend\controllers;
use yii\web\Controller;
class IndexController extends Controller {
    public function actionIndex() {
        $content = "hello";
        return $this->render('index', ['content' => $content]);
    }
}