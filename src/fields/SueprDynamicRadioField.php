<?php
namespace amici\SuperDynamicFields\fields;

use Craft;
use craft\fields\BaseOptionsField;
use craft\base\SortableFieldInterface;
use craft\base\ElementInterface;
use craft\helpers\Cp;

use amici\SuperDynamicFields\base\FieldTrait;
use amici\SuperDynamicFields\fields\data\SingleOptionFieldData;
use amici\SuperDynamicFields\assetbundles\SuperDynamicFieldsAsset;

class SueprDynamicRadioField extends BaseOptionsField implements SortableFieldInterface
{

    use FieldTrait;

    protected static bool $multi = false;
    private string $inputTemplate = "radio";

    public static function displayName(): string
    {
        return Craft::t('super-dynamic-fields', 'Radio Buttons [Super Dynamic Fields]');
    }

    public static function phpType(): string
    {
        return SingleOptionFieldData::class;
    }

    protected function inputHtml(mixed $value, ElementInterface $element = null, bool $inline = false): string
    {
        if($this->templateData == "" || ! $this->cachedOptions) {
            $this->json = $this->_parseTemplateJson($element);
        }

        /** @var SingleOptionFieldData $value */
        if (! $value->valid) {
            Craft::$app->getView()->setInitialDeltaValue($this->handle, null);
        }

        $view = Craft::$app->getView();
        $view->registerAssetBundle(SuperDynamicFieldsAsset::class);

        return Cp::renderTemplate('super-dynamic-fields/_field/input/' . $this->inputTemplate, [
            'id' => $this->getInputId(),
            'describedBy' => $this->describedBy,
            'name' => $this->handle,
            'value' => $this->encodeValue($value),
            'options' => $this->translatedOptions(true, $value, $element),
            'genError'    => $this->genError,
            'template'    => $this->templateData
        ]);

    }
}