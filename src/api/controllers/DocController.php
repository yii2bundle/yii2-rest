<?php

namespace yii2lab\rest\api\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii2rails\app\domain\helpers\EnvService;
use yii2rails\extension\yii\helpers\FileHelper;
use yii2lab\rest\domain\helpers\MiscHelper;
use yii2lab\rest\domain\helpers\postman\PostmanHelper;
use yii2lab\rest\domain\helpers\RouteHelper;
use yii2tool\restclient\domain\enums\ApiDocEnum;

/**
 * Class DocController
 *
 * @package yii2lab\rest\api\controllers
 *
 * @property \yii2lab\rest\api\Module $module
 */
class DocController extends Controller
{
	
	public function init() {
		if(!$this->module->isEnabledDoc) {
			throw new NotFoundHttpException('Documentation is disabled');
		}
		parent::init();
	}
	
	public function actionIndex() {
        return RouteHelper::allRoutes();
    }
	
	public function actionHtml() {
		$content = FileHelper::load(API_DIR . DS . API_VERSION_STRING . DS . 'docs' . DS . 'dist' . DS . 'index.html');
		if(empty($content)) {
			throw new NotFoundHttpException('Empty document');
		}
		Yii::$app->response->format = Response::FORMAT_HTML;
		$content = str_replace(ApiDocEnum::EXAMPLE_DOMAIN . SL, $_ENV['API_DOMAIN_URL'] . SL, $content);
		return $content;
	}
 
	public function actionPostman($version) {
		$apiVersion = MiscHelper::currentApiVersion();
		return PostmanHelper::generate($apiVersion, $version);
	}
	
	public function actionNormalizeCollection() {
		\App::$domain->rest->rest->normalizeTag();
	}
	
	public function actionExportCollection() {
		\App::$domain->rest->rest->exportCollection();
	}
	
	public function actionImportCollection() {
		\App::$domain->rest->rest->importCollection();
	}
}
