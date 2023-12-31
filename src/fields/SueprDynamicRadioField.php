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

    public $multi = false;
    private $inputTemplate = "radio";

    public static function displayName(): string
    {
        return Craft::t('super-dynamic-fields', 'Radio Buttons [Super Dynamic Fields]');
    }

    public static function valueType(): string
    {
        return SingleOptionFieldData::class;
    }

    protected function inputHtml($value, ElementInterface $element = null): string
    {
        if($this->templateData == "" || ! $this->cachedOptions) {
            $this->json = $this->_parseTemplateJson($element);
        }

        $view = Craft::$app->getView();

        /** @var SingleOptionFieldData $value */
        if (!$value->valid) {
            $view->setInitialDeltaValue($this->handle, null);
        }

        $view->registerAssetBundle(SuperDynamicFieldsAsset::class);

        return $view->renderTemplate('super-dynamic-fields/_field/input/' . $this->inputTemplate, [
            'describedBy' => $this->describedBy,
            'name' => $this->handle,
            'value' => $this->encodeValue($value),
            'options' => $this->translatedOptions(true),
            'genError'  => $this->genError,
            'template'  => $this->templateData
        ]);

    }
}