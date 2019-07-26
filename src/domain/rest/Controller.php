<?php

namespace yii2lab\rest\domain\rest;

use yii\rest\Controller as YiiController;
use yii2rails\app\domain\helpers\EnvService;
use yii2rails\domain\services\base\BaseService;
use yii2rails\extension\web\helpers\ControllerHelper;

/**
 * Class Controller
 *
 * @package yii2lab\rest\domain\rest
 *
 * @property null|string|BaseService
 */
class Controller extends YiiController {
	
	public $service = null;
	
	public function format() {
		return [];
	}

    public function actions()
    {
        $actions = parent::actions();
        $actions['options'] = [
            'class' => 'yii\rest\OptionsAction',
        ];
        return $actions;
    }

	public function init() {
		parent::init();
		$this->initService();
		$this->initFormat();
		$this->initBehaviors();
	}
	
	private function initBehaviors() {
		$controllerBehaviors = param('controllerBehaviors');
		if($controllerBehaviors) {
			$this->attachBehaviors($controllerBehaviors);
		}
	}
	
	private function initService() {
		if(empty($this->service) && !empty($this->serviceName)) {
			$this->service = $this->serviceName;
		}
		$this->service = ControllerHelper::forgeService($this->service);
	}
	
	private function initFormat() {
		$format = $this->format();
		$default = [
            'class' => Serializer::class,
            'format' => $format,
        ];
        $this->serializer = EnvService::get('response.serializer', $default);
	}

}
