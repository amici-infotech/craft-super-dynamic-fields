<?php
namespace amici\SuperDynamicFields\fields;

use Craft;
use craft\base\SortableFieldInterface;
use craft\base\ElementInterface;
use craft\helpers\ArrayHelper;
use craft\fields\BaseOptionsField;

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
        if($this->templateData == "" || ! $this->cachedOptions) {
            $this->json = $this->_parseTemplateJson($element);
        }

        $view = Craft::$app->getView();

        /** @var SingleOptionFieldData $value */
        $options = $this->translatedOptions(true);

        if (! $value->valid) {
            $view->setInitialDeltaValue($this->handle, $this->encodeValue($value->value));
            $value = null;

            // Add a blank option to the beginning if one doesn't already exist
            if (!ArrayHelper::contains($options, function($option) {
                return isset($option['value']) && $option['value'] === '';
            })) {
                array_unshift($options, ['label' => '', 'value' => '']);
            }
        }

        $view->registerAssetBundle(SuperDynamicFieldsAsset::class);

        return $view->renderTemplate('super-dynamic-fields/_field/input/' . $this->inputTemplate, [
            'id' => $this->getInputId(),
            'describedBy' => $this->describedBy,
            'name' => $this->handle,
            'value' => $this->encodeValue($value),
            'options' => $options,
            'genError'  => $this->genError,
            'template'  => $this->templateData
        ]);
    }
}