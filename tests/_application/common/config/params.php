<?php

use yii\helpers\ArrayHelper;
use yii2tool\test\helpers\TestHelper;

$config = [
	
];

$baseConfig = TestHelper::loadConfig('common/config/params.php');
return ArrayHelper::merge($baseConfig, $config);
