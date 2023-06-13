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

	public static Plugin $plugin;
	public string $schemaVersion = '2.0.0.4';
	// public string $minVersionRequired = '2.0.0';
	public bool $hasCpSettings = false;
	public bool $hasCpSection = false;

	public function init(): void
	{
	    parent::init();

	    self::$plugin = $this;
	    $this->_registerFields();
	    $this->_setPluginComponents();
	}

	private function _registerFields(): void
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

	protected function afterInstall(): void
	{

	}

	/*public function getCpNavItem()
	{
		$parent = parent::getCpNavItem();
		return $parent;
	}*/

	public function beforeUninstall(): void
	{

	}

}
