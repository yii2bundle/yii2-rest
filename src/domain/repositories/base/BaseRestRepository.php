<?php

namespace yii2bundle\rest\domain\repositories\base;

use yii2lab\rest\domain\traits\RestTrait;
use yii2rails\domain\repositories\BaseRepository;

abstract class BaseRestRepository extends BaseRepository {

	use RestTrait;
	
}
