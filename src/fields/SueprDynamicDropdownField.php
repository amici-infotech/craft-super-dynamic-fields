<?php
namespace amici\SuperDynamicFields\fields;

use Craft;

use craft\base\Field;
use craft\base\PreviewableFieldInterface;

use amici\SuperDynamicFields\base\FieldSettings;

class SueprDynamicDropdownField extends Field implements PreviewableFieldInterface
{

    use FieldSettings;

    public $template;
    public $templateData;
    public $json;
    public $genError;
    public $multi = false;
    private $inputTemplate = "dropdown";

	public static function displayName(): string
    {
        return Craft::t('super-dynamic-fields', 'Dropdown [Super Dyanmic Fields]');
    }

}