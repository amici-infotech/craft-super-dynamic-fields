<?php
namespace amici\SuperDynamicFields\fields;

use Craft;
use craft\fields\BaseOptionsField;
use craft\base\SortableFieldInterface;
use craft\base\ElementInterface;

use amici\SuperDynamicFields\base\FieldTrait;
use amici\SuperDynamicFields\fields\data\SingleOptionFieldData;
use amici\SuperDynamicFields\assetbundles\SuperDynamicFieldsAsset;

class SueprDynamicRadioField extends BaseOptionsField implements SortableFieldInterface
{

    use FieldTrait;

    public bool $multi = false;
    private string $inputTemplate = "radio";

    public static function displayName(): string
    {
        return Craft::t('super-dynamic-fields', 'Radio Buttons [Super Dynamic Fields]');
    }

    public static function valueType(): string
    {
        return SingleOptionFieldData::class;
    }

    protected function inputHtml(mixed $value, ElementInterface $element = null): string
    {
        if($this->templateData == "" || ! $this->cachedOptions) {
            $this->json = $this->_parseTemplateJson($element);
        }

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