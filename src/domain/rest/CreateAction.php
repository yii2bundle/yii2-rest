<?php

namespace yii2lab\rest\domain\rest;

use Yii;
use yii\helpers\ArrayHelper;
use yii2rails\extension\web\enums\ActionEventEnum;

class CreateAction extends BaseAction {

	public $serviceMethod = 'create';
	public $successStatusCode = 201;
	
	public function run() {
		$body = Yii::$app->request->getBodyParams();
		$body = $this->callActionTrigger(ActionEventEnum::BEFORE_WRITE, $body);
		$response = $this->runServiceMethod1($body);
		$response = $this->callActionTrigger(ActionEventEnum::AFTER_WRITE, $response);
		$id = ArrayHelper::getValue($response, 'id');
        Yii::$app->response->headers->add('X-Entity-Id', $id);
        if($this->successStatusCode != 200) {
            $response = null;
        }
		return $response;
	}
}
