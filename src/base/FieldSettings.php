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

        if($this->multi)
        {
            if(is_array($value))
            {
                $temp = $value;
                $value = [];
                foreach ($temp as $key => $val)
                {
                    $value[] = $val->value;
                }
            }
            else
            {
                $value = null;
            }
        }
        else
        {
            $value = isset($value->value) ? $value->value : "";
        }

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
                    if($this->multi)
                    {
                        $options[] = [
                            'value' => $val['value'],
                            'label' => $val['label'],
                        ];
                    }
                    else
                    {
                        $options[$val['value']] = $val['label'];
                    }
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

    public function normalizeValue($value, ElementInterface $element = null)
    {

        if($value instanceof DynamicField)
        {
            if($value->value == "" && $value->label == "")
            {
                return null;
            }

            return $value;
        }
        elseif($value == "" && ! $this->isFresh($element))
        {
            return null;
        }

        $this->json = $this->_parseTemplateJson();

        if($this->multi)
        {
            return $this->_parseMulti($value, $element);
        }
        else
        {
            return $this->_parseSingle($value, $element);
        }

    }

    private function _parseSingle($value, ElementInterface $element = null)
    {

        $currentVal = new DynamicField();
        $currentVal->value = $value;

        if (is_array($this->json))
        {

            foreach ($this->json as $key => $val)
            {

                if($this->isFresh($element) && isset($val['default']) && in_array(strtolower($val['default']), [true, 'true', 'yes', 'y']))
                {
                    $currentVal->value = $val['value'];
                }
                elseif($val['value'] == $value)
                {

                    $currentVal->value   = $val['value'];
                    $currentVal->label   = $val['label'];
                    $currentVal->default = ((isset($val['default']) && in_array(strtolower($val['default']), [true, 'true', 'yes', 'y'])) ? true : false);
                    $currentVal->extras  = $val;

                    unset($currentVal->extras['value']);
                    unset($currentVal->extras['label']);
                    unset($currentVal->extras['default']);

                }

            }

        }

        return $currentVal;

    }

    private function _parseMulti($value, ElementInterface $element = null)
    {

        if (! is_array($value))
        {
            $value = @json_decode($value, true);
        }

        if (! is_array($value))
        {
            $value = [];
        }

        $return = [];
        $cnt = 0;
        if (is_array($this->json))
        {

            foreach ($this->json as $key => $val)
            {

                if($this->isFresh($element) && isset($val['default']) && in_array(strtolower($val['default']), [true, 'true', 'yes', 'y']))
                {
                    $return[$cnt] = new DynamicField();
                    $return[$cnt]->value = $val['value'];
                    $cnt++;
                }
                elseif(in_array($val['value'], $value))
                {

                    $return[$cnt] = new DynamicField();

                    $return[$cnt]->value   = $val['value'];
                    $return[$cnt]->label   = $val['label'];
                    $return[$cnt]->default = ((isset($val['default']) && in_array(strtolower($val['default']), [true, 'true', 'yes', 'y'])) ? true : false);
                    $return[$cnt]->extras  = $val;

                    unset($return[$cnt]->extras['value']);
                    unset($return[$cnt]->extras['label']);
                    unset($return[$cnt]->extras['default']);
                    $cnt++;

                }

            }

        }

        return $return;

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