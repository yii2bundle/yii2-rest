<?php

namespace yii2lab\rest\domain\entities;

use yii2rails\domain\BaseEntity;

/**
 * Class ResponseEntity
 * @package yii2lab\rest\domain\entities
 *
 * @property $content
 * @property array $data
 * @property array $headers
 * @property array $cookies
 * @property integer $status_code
 * @property string $format
 * @property boolean $is_ok
 * @property integer $duration
 *
 * @property RequestEntity $request
 */
class ResponseEntity extends BaseEntity {

    protected $content = [];
	protected $data = [];
	protected $headers = [];
    protected $cookies = [];
    protected $status_code = 200;
    protected $format;
	protected $duration;

    protected $request;

    public function getIsOk() {
        return strncmp('20', $this->status_code, 2) === 0;
    }

    public function fieldType() {
        return [
            'status_code' => 'integer',
            'request' => RequestEntity::class,
        ];
    }

    public function getHeader($key, $asArray = false) {
        $key = mb_strtolower($key);

        $value = $this->headers[$key];
        if($asArray) {
            $arr = explode(';', $value);
            $value = array_map('trim', $arr);
        }
        return $value;
    }

}