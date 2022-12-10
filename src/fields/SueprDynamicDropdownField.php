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

    public bool $multi = false;
    private string $inputTemplate = "dropdown";
    protected bool $optgroups = true;

	public static function displayName(): string
    {
        return Craft::t('super-dynamic-fields', 'Dropdown [Super Dynamic Fields]');
    }

    public static function valueType(): string
    {
        return SingleOptionFieldData::class;
    }

    protected function inputHtml(mixed $value, ?ElementInterface $element = null): string
    {
        $this->element = $element;

        /** @var SingleOptionFieldData $value */
        $options = $this->translatedOptions();

        /** @var SingleOptionFieldData $value */
        if (! $value->valid) {
            Craft::$app->getView()->setInitialDeltaValue($this->handle, $value->getValue());
        }

        $view           = Craft::$app->getView();
        $mode           = $view->getTemplateMode();
        $id             =  $this->getInputId();
        $nameSpacedId   = $view->namespaceInputId($id);

        $view->registerAssetBundle(SuperDynamicFieldsAsset::class);

        return $view->renderTemplate('super-dynamic-fields/_field/input/' . $this->inputTemplate, [
            'id'          => $id,
            'describedBy' => $this->describedBy,
            'name'        => $this->handle,
            'value'       => $value,
            'options'     => $options,
            'genError'    => $this->genError,
            'template'    => $this->templateData
        ]);

    }

}