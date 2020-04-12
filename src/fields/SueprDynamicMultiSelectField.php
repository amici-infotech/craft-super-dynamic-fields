<?php
namespace amici\SuperDynamicFields\fields;

use Craft;

use craft\base\Field;
// use craft\fields\BaseOptionsField;
use craft\base\PreviewableFieldInterface;

use amici\SuperDynamicFields\base\FieldSettings;

class SueprDynamicMultiSelectField extends Field implements PreviewableFieldInterface
{

    use FieldSettings;

    public $template;
    public $templateData;
    public $json;
    public $genError;
    public $multi = true;
    private $inputTemplate = "multiselect";

    public function init()
    {
        parent::init();
        $this->multi = true;
    }

	public static function displayName(): string
    {
        return Craft::t('super-dynamic-fields', 'Multi-select [Super Dynamic Fields]');
    }

}