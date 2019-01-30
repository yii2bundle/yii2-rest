<?php

namespace yii2lab\rest\domain\rest;

use yii\data\BaseDataProvider;
use yii2lab\extension\web\enums\ActionEventEnum;
use yii2lab\extension\web\helpers\ClientHelper;

class IndexActionWithQuery extends BaseAction {

	public $serviceMethod = 'getDataProvider';
	
	public function run() {
		$this->callActionTrigger(ActionEventEnum::BEFORE_READ);
		$query = ClientHelper::getQueryFromRequest();
		$response = $this->runServiceMethod($query);
		$response = $this->callActionTrigger(ActionEventEnum::AFTER_READ, $response);
		if($response instanceof BaseDataProvider) {
		    $page = $query->getParam('page');
            $dataProviderPage = $response->pagination->pageCount;
            if($page > $dataProviderPage) {
                $response->models = [];
            }
        }
		return $response;
	}

}
