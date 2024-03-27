<?php
namespace amici\SuperDynamicFields\fields;

use Craft;
use craft\base\SortableFieldInterface;
use craft\base\ElementInterface;
use craft\fields\BaseOptionsField;
use craft\helpers\ArrayHelper;

use amici\SuperDynamicFields\base\FieldTrait;
use amici\SuperDynamicFields\fields\data\MultiOptionsFieldData;
use amici\SuperDynamicFields\assetbundles\SuperDynamicFieldsAsset;

class SueprDynamicCheckboxesField extends BaseOptionsField
{

    use FieldTrait;

    protected static bool $multi = true;
    private string $inputTemplate = "checkboxes";

    public static function displayName(): string
    {
        return Craft::t('super-dynamic-fields', 'Checkboxes [Super Dynamic Fields]');
    }

    public static function phpType(): string
    {
        return MultiOptionsFieldData::class;
    }

    protected function inputHtml(mixed $value, ElementInterface $element = null, bool $inline = false): string
    {
        if($this->templateData == "" || ! $this->cachedOptions) {
            $this->json = $this->_parseTemplateJson($element);
        }

        /** @var MultiOptionsFieldData $value */
        if (ArrayHelper::contains($value, 'valid', false, true)) {
            Craft::$app->getView()->setInitialDeltaValue($this->handle, null);
        }

        $view = Craft::$app->getView();
        $view->registerAssetBundle(SuperDynamicFieldsAsset::class);
        return $view->renderTemplate('super-dynamic-fields/_field/input/' . $this->inputTemplate, [
            'id'        => $this->getInputId(),
            'name'      => $this->handle,
            'options'   => $this->translatedOptions(),
            'values'     => $value,
            'genError'  => $this->genError,
            'template'  => $this->templateData,
        ]);

    }

}
