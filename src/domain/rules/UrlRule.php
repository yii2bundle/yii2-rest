<?php

namespace yii2lab\rest\domain\rules;

use Yii;

class UrlRule extends \yii\rest\UrlRule {

    public $tokens = [
        '{id}' => '<id:[^\/]+>',
    ];

}
