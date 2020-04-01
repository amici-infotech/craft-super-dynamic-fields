<?php
namespace amici\SuperDynamicFields\fields;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;

use amici\SuperDynamicFields\SuperDynamicFields;

class SueprDynamicDropdownField extends Field
{

	public static function displayName(): string
    {
        return Craft::t('super-dynamic-fields', 'Dropdown [Super Dyanmic Fields]');
    }

    public static function defaultSelectionLabel(): string
    {
        return Craft::t('super-dynamic-fields', 'Select JSON template');
    }

    public function getContentColumnType(): string
    {
        return Schema::TYPE_TEXT;
    }

    protected function optionsSettingLabel(): string
    {
        return Craft::t('super-dynamic-fields', 'Field Options');
    }

    public function getInputHtml($value, ElementInterface $element = null): string
    {

        $id = Craft::$app->getView()->formatInputId($this->handle);
        $nameSpacedId = Craft::$app->getView()->namespaceInputId($id);

        return Craft::$app->getView()->renderTemplate('super-dynamic-fields/_field/input', [
            'id' => $id,
            'name' => $this->handle,
            'value' => $value,
            'options' => ['1' => '1', '2' => '2'],
        ]);
    }

    public function getSettingsHtml()
    {
        return Craft::$app->getView()->renderTemplate('super-dynamic-fields/_field/settings', [

        ]);
    }

}