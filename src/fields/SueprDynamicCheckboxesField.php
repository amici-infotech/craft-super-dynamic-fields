<?php
namespace amici\SuperDynamicFields\fields;

use Craft;

use craft\base\Field;
// use craft\fields\BaseOptionsField;
use craft\base\PreviewableFieldInterface;

use amici\SuperDynamicFields\base\FieldSettings;

class SueprDynamicCheckboxesField extends Field implements PreviewableFieldInterface
{

    use FieldSettings;

    public $template;
    public $templateData;
    public $json;
    public $genError;
    public $multi = true;
    private $inputTemplate = "checkboxes";

    public function init()
    {
        parent::init();
        $this->multi = true;
    }

	public static function displayName(): string
    {
        return Craft::t('super-dynamic-fields', 'Checkboxes [Super Dynamic Fields]');
    }

}