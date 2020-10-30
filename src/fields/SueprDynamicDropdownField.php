<?php
namespace amici\SuperDynamicFields\fields;

use Craft;
use craft\fields\BaseOptionsField;
use craft\base\SortableFieldInterface;
use craft\base\ElementInterface;

use amici\SuperDynamicFields\base\FieldTrait;
use amici\SuperDynamicFields\fields\data\SingleOptionFieldData;
use amici\SuperDynamicFields\assetbundles\SuperDynamicFieldsAsset;

class SueprDynamicDropdownField extends BaseOptionsField implements SortableFieldInterface
{

    use FieldTrait;

    public $multi = false;
    private $inputTemplate = "dropdown";

	public static function displayName(): string
    {
        return Craft::t('super-dynamic-fields', 'Dropdown [Super Dynamic Fields]');
    }

    public static function valueType(): string
    {
        return SingleOptionFieldData::class;
    }

    protected function inputHtml($value, ElementInterface $element = null): string
    {
        /** @var SingleOptionFieldData $value */
        if (! $value->valid) {
            Craft::$app->getView()->setInitialDeltaValue($this->handle, null);
        }

        $view           = Craft::$app->getView();
        $mode           = $view->getTemplateMode();
        $id             = $view->formatInputId($this->handle);
        $nameSpacedId   = $view->namespaceInputId($id);

        $view->registerAssetBundle(SuperDynamicFieldsAsset::class);

        return $view->renderTemplate('super-dynamic-fields/_field/input/' . $this->inputTemplate, [
            'id'        => $id,
            'name'      => $this->handle,
            'options'   => $this->translatedOptions(),
            'value'     => $value,
            'genError'  => $this->genError,
            'template'  => $this->templateData
        ]);

    }
}