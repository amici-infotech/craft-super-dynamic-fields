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

    public static function valueType(): string
    {
        return MultiOptionsFieldData::class;
    }

    protected function inputHtml($value, ElementInterface $element = null): string
    {
        if($this->templateData == "" || ! $this->cachedOptions) {
            $this->json = $this->_parseTemplateJson($element);
        }

        /** @var MultiOptionsFieldData $value */
        if (ArrayHelper::contains($value, 'valid', false, true)) {
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
            'values'     => $value,
            'genError'  => $this->genError,
            'template'  => $this->templateData,
        ]);

    }

}
