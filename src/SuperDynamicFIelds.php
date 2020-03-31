<?php
namespace amici\SuperDynamicFields;

use Craft;
use yii\base\Event;
use craft\base\Plugin;
use craft\events\RegisterUrlRulesEvent;
use craft\web\twig\variables\CraftVariable;
use craft\web\UrlManager;

use amici\SuperDynamicFields\base\PluginTrait;
use amici\SuperDynamicFields\models\Settings;

class SuperDynamicFields extends Plugin
{

	use PluginTrait;

	public static $app;
	public static $plugin;
	public $hasCpSection 		= false;
	public $hasCpSettings 		= false;
    public static $pluginHandle = 'super-dynamic-fields';
	public $schemaVersion 		= '1.0.0';
	public $minVersionRequired 	= '3.0.0';

	public function init()
	{

	    parent::init();

	    self::$plugin = $this;
	    // self::$app = new App();
	    $this->_setPluginComponents();

	}

	protected function createSettingsModel(): Settings
	{
	    return new Settings();
	}

	protected function afterInstall()
	{

	}

	/*public function getCpNavItem()
	{
		$parent = parent::getCpNavItem();
		return $parent;
	}*/

	public function beforeUninstall(): bool
	{
		return true;
	}

}
?>