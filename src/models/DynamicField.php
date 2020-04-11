<?php
namespace amici\SuperDynamicFields\models;

use Craft;

use amici\SuperDynamicFields\SuperDynamicFields;
use amici\SuperDynamicFields\base\Field;

class DynamicField extends Field
{

    public $value = "";
    public $label = "";
    public $default = false;

    public static function defaultLabel(): string
    {
        return Craft::t('super-dynamic-fields', 'Dropdown [Super Dyanmic Fields]');
    }

    public static function settingsTemplatePath(): string
    {
        return 'super-dynamic-fields/_field/settings';
    }

    public static function defaultPlaceholder(): string
    {
        return Craft::t('super-dynamic-fields', 'Select JSON template');
    }

    public function getValue(): string
    {
        return (string) $this->value;
    }

    public function getLabel(): string
    {
        return (string) Craft::getAlias($this->label);
    }

    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [['template'], 'required'];
        return $rules;
    }

}
