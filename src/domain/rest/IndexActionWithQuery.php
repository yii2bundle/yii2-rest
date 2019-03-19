<?php

namespace yii2lab\rest\domain\rest;

use yii\data\BaseDataProvider;
use yii2rails\extension\web\enums\ActionEventEnum;
use yii2rails\extension\web\helpers\ClientHelper;

class IndexActionWithQuery extends BaseAction {

	public $serviceMethod = 'getDataProvider';
	public $query = null;
	
	public function run() {
		$this->callActionTrigger(ActionEventEnum::BEFORE_READ);
		$query = ClientHelper::getQueryFromRequest(null, $this->query);
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
