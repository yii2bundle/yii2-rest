<?php

namespace yii2lab\rest\domain\rest;

use Yii;
use yii2mod\helpers\ArrayHelper;
use yii2rails\domain\BaseEntity;
use yii2rails\extension\web\enums\ActionEventEnum;
use yii2rails\extension\web\enums\HttpHeaderEnum;

class CreateAction extends BaseAction {

	public $serviceMethod = 'create';
	public $successStatusCode = 201;
	
	public function run() {
		$body = Yii::$app->request->getBodyParams();
		$body = $this->callActionTrigger(ActionEventEnum::BEFORE_WRITE, $body);
		$response = $this->runServiceMethod1($body);
		$response = $this->callActionTrigger(ActionEventEnum::AFTER_WRITE, $response);
		if($response instanceof BaseEntity && $response->hasProperty('id')) {
            $id = ArrayHelper::getValue($response, 'id');
            Yii::$app->response->headers->add(HttpHeaderEnum::X_ENTITY_ID, $id);
        }
        if($this->successStatusCode != 200) {
            $response = null;
        }
		return $response;
	}
}
