<?php

namespace yii2lab\rest\domain\helpers;

use League\Flysystem\File;
use Yii;
use yii2rails\extension\common\helpers\ClassHelper;
use yii2rails\extension\common\helpers\ModuleHelper;
use yii2rails\extension\yii\helpers\FileHelper;
use yii2lab\rest\domain\enums\ApiDocEnum;

class ApiDocHelper {
	
	public static function getModulesConfig($version) {
		$modules = ModuleHelper::loadConfigFromAppTree(['api', 'api/v' . $version]);
		$result = [];
		foreach($modules as $id => $definition) {
			$definition['path'] = ClassHelper::getNamespace($definition['class']);
			try {
				$definition['docPath'] = FileHelper::getAlias('@' . $definition['path']) . DS . 'docs';
				$definition['docDirs'] = FileHelper::scanDir($definition['docPath']);
				if(!empty($definition['docDirs'])) {
					$result[$id] = $definition;
				}
			} catch(\yii\base\InvalidArgumentException $e) {}
		}
		return $result;
	}
	
	public static function getApiRamls($version) {
		$modules = ApiDocHelper::getModulesConfig($version);
		$result = [];
		foreach($modules as $definition) {
			foreach($definition['docDirs'] as $docDir) {
				$result[] = FileHelper::trimRootPath($definition['docPath'] . DS . $docDir . DS . 'api.raml');
			}
		}
		return $result;
	}
	
	public static function generate($version) {
		$mainContent = self::generateApiRaml($version);
		$mainContent = trim($mainContent);
		FileHelper::save(ROOT_DIR . '/api/v' . $version . '/docs/api.raml', $mainContent);
		FileHelper::save(ROOT_DIR . '/api/v' . $version . '/docs/dist/.gitignore', EMP);
		
		$cmdContent = self::generateCmd($version);
		$cmdContent = trim($cmdContent);
		FileHelper::save(ROOT_DIR . '/api/v' . $version . '/docs/cmd/generate.bat', $cmdContent);
	}
	
	public static function generateCmd($version) {
		return '
cd ..
raml2html "./api.raml" > "./dist/index.html"
pause
		';
	}
	
	public static function generateApiRaml($version) {
		$ramls = ApiDocHelper::getApiRamls($version);
		$ramlsString = '';
		foreach($ramls as $definition) {
			$name = FileHelper::up($definition);
			$name = basename($name);
			$definition = str_replace(BSL, SL, $definition);
			$ramlsString .= '/'.$name.': !include ../../../' . $definition . PHP_EOL;
		}
		
		return '
#%RAML 1.0
title: '.Yii::$app->name.'
version: v' . $version . '
protocols: [ HTTP, HTTPS ]
baseUri: ' . ApiDocEnum::EXAMPLE_DOMAIN . '/v' . $version . '

mediaType: [ application/json, application/xml ]

securitySchemes:
  Auth: !include ../../../vendor/zndoc/rest-api/src/securitySchemes/base.raml
#securedBy: [ Auth ]

traits:
  paged:   !include ../../../vendor/zndoc/rest-api/src/traits/paged.raml
  search:   !include ../../../vendor/zndoc/rest-api/src/traits/search.raml
  lang:    !include ../../../vendor/zndoc/rest-api/src/traits/lang.raml
  timezone: !include ../../../vendor/zndoc/rest-api/src/traits/timezone.raml
  fields:  !include ../../../vendor/zndoc/rest-api/src/traits/fields.raml
  expand:  !include ../../../vendor/zndoc/rest-api/src/traits/expand.raml
  code200: !include ../../../vendor/zndoc/rest-api/src/traits/codes/200.raml
  code201: !include ../../../vendor/zndoc/rest-api/src/traits/codes/201.raml
  code204: !include ../../../vendor/zndoc/rest-api/src/traits/codes/204.raml
  code304: !include ../../../vendor/zndoc/rest-api/src/traits/codes/304.raml
  code400: !include ../../../vendor/zndoc/rest-api/src/traits/codes/400.raml
  code401: !include ../../../vendor/zndoc/rest-api/src/traits/codes/401.raml
  code403: !include ../../../vendor/zndoc/rest-api/src/traits/codes/403.raml
  code404: !include ../../../vendor/zndoc/rest-api/src/traits/codes/404.raml
  code405: !include ../../../vendor/zndoc/rest-api/src/traits/codes/405.raml
  code415: !include ../../../vendor/zndoc/rest-api/src/traits/codes/415.raml
  code422: !include ../../../vendor/zndoc/rest-api/src/traits/codes/422.raml
  code429: !include ../../../vendor/zndoc/rest-api/src/traits/codes/429.raml
  code500: !include ../../../vendor/zndoc/rest-api/src/traits/codes/500.raml

' . $ramlsString;
	}
	
}