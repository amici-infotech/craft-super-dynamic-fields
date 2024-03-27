<?php
namespace amici\SuperDynamicFields\fields;

use Craft;
use craft\base\SortableFieldInterface;
use craft\base\ElementInterface;
use craft\fields\BaseOptionsField;
use craft\helpers\ArrayHelper;
use craft\helpers\Cp;

use amici\SuperDynamicFields\base\FieldTrait;
use amici\SuperDynamicFields\fields\data\MultiOptionsFieldData;
use amici\SuperDynamicFields\assetbundles\SuperDynamicFieldsAsset;

class SueprDynamicMultiSelectField extends BaseOptionsField
{

    use FieldTrait;

    protected static bool $multi = true;
    private string $inputTemplate = "multiselect";

    public static function displayName(): string
    {
        return Craft::t('super-dynamic-fields', 'Multi-select [Super Dynamic Fields]');
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

        $view           = Craft::$app->getView();
        $view->registerAssetBundle(SuperDynamicFieldsAsset::class);

        // $id             = $view->formatInputId($this->handle);
        /* return $view->renderTemplate('super-dynamic-fields/_field/input/' . $this->inputTemplate, [
            'id'        => $id,
            'name'      => $this->handle,
            'options'   => $this->translatedOptions(),
            'values'     => $value,
            'genError'  => $this->genError,
            'template'  => $this->templateData,
        ]); */

        return Cp::renderTemplate('super-dynamic-fields/_field/input/' . $this->inputTemplate, [
            'id'        => $this->getInputId(),
            'describedBy' => $this->describedBy,
            'class' => 'selectize',
            'name'      => $this->handle,
            'options'   => $this->translatedOptions(true, $value, $element),
            'values'    => $this->encodeSdfValue($value),
            'multi' => true,
            'genError'  => $this->genError,
            'template'  => $this->templateData,
        ]);

    }

    /**
     * @inheritdoc
     */
    public function getStaticHtml(mixed $value, \craft\base\ElementInterface $element = null): string
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

        return Cp::renderTemplate('super-dynamic-fields/_field/input/' . $this->inputTemplate, [
            'id'        => $this->getInputId(),
            'describedBy' => $this->describedBy,
            'class' => 'selectize',
            'name'      => $this->handle,
            'options'   => $this->translatedOptions(true, $value, $element),
            'values'    => $this->encodeSdfValue($value),
            'multi' => true,
            'genError'  => $this->genError,
            'template'  => $this->templateData,
        ]);
    }

}
