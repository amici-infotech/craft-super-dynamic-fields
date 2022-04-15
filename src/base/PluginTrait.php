<?php
namespace amici\SuperDynamicFields\base;

use Craft;
use yii\log\Logger;

// use amici\SuperDynamicFields\SuperDynamicFields;
use amici\SuperDynamicFields\libraries\General;

trait PluginTrait
{

    private function _setPluginComponents()
    {
        $this->setComponents([
            'general' => General::class,
        ]);
    }

    public static function t($message, array $params = [])
    {
    	return Craft::t('super-dynamic-fields', $message, $params);
    }

    public static function log($message, $type = 'info')
    {
    	Craft::$type(self::t($message), __METHOD__);
    }

    public static function info($message)
    {
    	Craft::info(self::t($message), __METHOD__);
    }

    public static function error($message)
    {
    	Craft::error(self::t($message), __METHOD__);
    }

}