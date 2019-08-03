<?php

use yii\helpers\ArrayHelper;
use yii2rails\domain\enums\Driver;
use yii2tool\test\helpers\TestHelper;

$config = [
];

$baseConfig = TestHelper::loadConfig('common/config/domains.php');
return ArrayHelper::merge($baseConfig, $config);
