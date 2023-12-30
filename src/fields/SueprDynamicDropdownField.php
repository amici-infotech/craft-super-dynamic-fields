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

class SueprDynamicDropdownField extends BaseOptionsField implements SortableFieldInterface
{

    use FieldTrait;

    public bool $multi = false;
    // private string $inputTemplate = "_dropdown";
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
        if($this->templateData == "" || ! $this->cachedOptions) {
            $this->json = $this->_parseTemplateJson($element);
        }

        /** @var SingleOptionFieldData $value */
        $options = $this->translatedOptions(true, $value, $element);

        /** @var SingleOptionFieldData $value */
        if (! $value->valid) {
            Craft::$app->getView()->setInitialDeltaValue($this->handle, $value->getValue());
        }

        $view           = Craft::$app->getView();
        $mode           = $view->getTemplateMode();
        $id             =  $this->getInputId();
        $nameSpacedId   = $view->namespaceInputId($id);

        $view->registerAssetBundle(SuperDynamicFieldsAsset::class);

        $encValue = $this->encodeValue($value);
        if ($encValue === null || $encValue === '') {
            $encValue = '__BLANK__';
        }

        /* return $view->renderTemplate('super-dynamic-fields/_field/input/' . $this->inputTemplate, [
            'id'          => $id,
            'describedBy' => $this->describedBy,
            'name'        => $this->handle,
            'value'       => $value,
            'options'     => $options,
            'genError'    => $this->genError,
            'template'    => $this->templateData
        ]); */

        /* return Cp::selectizeHtml([
            'id' => $this->getInputId(),
            'describedBy' => $this->describedBy,
            'name' => $this->handle,
            'value' => $encValue,
            'options' => $options,
        ]); */

        return Cp::renderTemplate('super-dynamic-fields/_field/input/' . $this->inputTemplate, [
            'id' => $this->getInputId(),
            'describedBy' => $this->describedBy,
            'name' => $this->handle,
            'value' => $encValue,
            'options' => $options,
            'genError'    => $this->genError,
            'template'    => $this->templateData
        ]);

    }

}