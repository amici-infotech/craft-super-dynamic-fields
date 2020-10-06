<?php
namespace amici\SuperDynamicFields;

use Craft;
use yii\base\Event;
use craft\base\Plugin;
use craft\events\RegisterUrlRulesEvent;
use craft\events\RegisterComponentTypesEvent;
use craft\services\Fields;
use craft\web\twig\variables\CraftVariable;
use craft\web\UrlManager;

use amici\SuperDynamicFields\base\PluginTrait;
use amici\SuperDynamicFields\models\Settings;
use amici\SuperDynamicFields\fields\SueprDynamicDropdownField;
use amici\SuperDynamicFields\fields\SueprDynamicRadioField;
use amici\SuperDynamicFields\fields\SueprDynamicCheckboxesField;
use amici\SuperDynamicFields\fields\SueprDynamicMultiSelectField;

class SuperDynamicFields extends Plugin
{

	use PluginTrait;

	public static $app;
	public static $plugin;
	public $hasCpSection 		= false;
	public $hasCpSettings 		= false;
    public static $pluginHandle = 'super-dynamic-fields';
	public $schemaVersion 		= '1.0.4';

	public function init()
	{

	    parent::init();

	    self::$plugin = $this;
	    // self::$app = new App();
	    $this->_registerFields();
	    $this->_setPluginComponents();

	}

	private function _registerFields()
	{
		Event::on(Fields::class, Fields::EVENT_REGISTER_FIELD_TYPES, function(RegisterComponentTypesEvent $event) {
			$event->types[] = SueprDynamicDropdownField::class;
			$event->types[] = SueprDynamicRadioField::class;
			$event->types[] = SueprDynamicCheckboxesField::class;
			$event->types[] = SueprDynamicMultiSelectField::class;
		});
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
