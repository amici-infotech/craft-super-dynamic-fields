<?php
namespace amici\SuperDynamicFields\base;

use Craft;
use yii\db\Schema;
use craft\base\ElementInterface;

use amici\SuperDynamicFields\models\DynamicField;
use amici\SuperDynamicFields\assetbundles\SuperDynamicFieldsAsset;

trait FieldSettings
{

    public static function defaultSelectionLabel(): string
    {
        return Craft::t('super-dynamic-fields', 'Select JSON template');
    }

    public function getContentColumnType(): string
    {
        return Schema::TYPE_TEXT;
    }

    public static function hasContentColumn(): bool
    {
        return true;
    }

    public function isValueEmpty($value, ElementInterface $element): bool
    {
        return empty($value->value ?? '');
    }

    public function getSettingsHtml()
    {
        return Craft::$app->getView()->renderTemplate('super-dynamic-fields/_field/settings', [
            'settings' => $this,
        ]);
    }

    protected function defineRules(): array
    {
        $rules = parent::defineRules();
        $rules[] = [['template'], 'required'];
        return $rules;
    }

    public function serializeValue($value, ElementInterface $element = null)
    {
        $value = isset($value->value) ? $value->value : "";
        return parent::serializeValue($value, $element);
    }

    public function getInputHtml($value, ElementInterface $element = null): string
    {

        $view           = Craft::$app->getView();
        $mode           = $view->getTemplateMode();
        $id             = $view->formatInputId($this->handle);
        $nameSpacedId   = $view->namespaceInputId($id);

        $options    = [];
        $template   = "";

        if($this->templateData == "")
        {
            $this->json = $this->_parseTemplateJson();
        }

        if(is_array($this->json))
        {
            foreach ($this->json as $key => $val)
            {
                if(isset($val['value']) && isset($val['label']))
                {
                    $options[$val['value']] = $val['label'];
                }
            }
        }

        $view->registerAssetBundle(SuperDynamicFieldsAsset::class);
        return $view->renderTemplate('super-dynamic-fields/_field/input/' . $this->inputTemplate, [
            'id'        => $id,
            'name'      => $this->handle,
            'options'   => $options,
            'value'     => $value,
            'genError'  => $this->genError,
            'template'  => $this->templateData
        ]);

    }

    protected function parseSingle($value, ElementInterface $element = null)
    {

        if($value instanceof DynamicField)
        {
            if($value->value == "" && $value->label == "")
            {
                return null;
            }

            return $value;
        }
        elseif($value == "")
        {
            return null;
        }

        $this->json = $this->_parseTemplateJson();

        $currentVal = new DynamicField();
        $currentVal->value = $value;

        if (is_array($this->json))
        {

            foreach ($this->json as $key => $val)
            {

                if($val['value'] == $value)
                {

                    $temp  = $val;

                    $currentVal->value   = $temp['value'];
                    $currentVal->label   = $temp['label'];
                    $currentVal->default = ((isset($temp['default']) && in_array(strtolower($temp['default']), ['true', 'yes', 'y'])) ? true : false);
                    $currentVal->extras  = $temp;

                    unset($currentVal->extras['value']);
                    unset($currentVal->extras['label']);
                    unset($currentVal->extras['default']);

                }

            }

        }

        return $currentVal;

    }

    private function _parseTemplateJson()
    {

        $view       = Craft::$app->getView();
        $mode       = $view->getTemplateMode();
        $variables  = [];
        $json       = false;

        try
        {

            $view->setTemplateMode($view::TEMPLATE_MODE_SITE);
            $this->templateData = $view->renderTemplate($this->template, $variables);
            $view->setTemplateMode($mode);
            $json = json_decode($this->templateData, true);;

            if(! is_array($json))
            {
                $this->genError = "Output string is not a valid JSON";
            }

        }
        catch (\ErrorException $e)
        {
            $view->setTemplateMode($mode);
            $this->genError = $e->getMessage();
        }
        catch (\Exception $e)
        {
            $view->setTemplateMode($mode);
            $this->genError = $e->getMessage();
        }

        return $json;

    }
}