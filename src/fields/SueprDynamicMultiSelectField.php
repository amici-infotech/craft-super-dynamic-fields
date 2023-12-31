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

class SueprDynamicMultiSelectField extends BaseOptionsField
{

    use FieldTrait;

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

    public static function valueType(): string
    {
        return MultiOptionsFieldData::class;
    }

    protected function inputHtml($value, ElementInterface $element = null): string
    {
        if($this->templateData == "" || ! $this->cachedOptions) {
            $this->json = $this->_parseTemplateJson($element);
        }

        $view = Craft::$app->getView();

        /** @var MultiOptionsFieldData $value */
        if (ArrayHelper::contains($value, 'valid', false, true)) {
            $view->setInitialDeltaValue($this->handle, null);
        }

        return $view->renderTemplate('super-dynamic-fields/_field/input/' . $this->inputTemplate, [
            'id' => $this->getInputId(),
            'describedBy' => $this->describedBy,
            'name' => $this->handle,
            'values' => $this->encodeValue($value),
            'options' => $this->translatedOptions(true),
            'genError'  => $this->genError,
            'template'  => $this->templateData,
        ]);
    }
}
