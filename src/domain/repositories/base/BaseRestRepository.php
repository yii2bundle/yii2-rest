<?php

namespace yii2bundle\rest\domain\repositories\base;

use yii2rails\domain\repositories\BaseRepository;
use yii2tool\restclient\domain\traits\RestTrait;

abstract class BaseRestRepository extends BaseRepository {

	use RestTrait;
	
}
