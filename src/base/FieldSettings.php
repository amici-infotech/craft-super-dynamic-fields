<?php
namespace amici\SuperDynamicFields\base;

use Craft;
use yii\db\Schema;
use craft\base\ElementInterface;

use amici\SuperDynamicFields\models\DynamicField;
use amici\SuperDynamicFields\assetbundles\SuperDynamicFieldsAsset;

trait FieldSettings
{

    protected function optionsSettingLabel(): string
    {
        return Craft::t('super-dynamic-fields', 'Field Options');
    }

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

        // $this->_debug(1, $value);
        if($this->multi)
        {
            // $this->_debug(2, $value);
            if(is_array($value))
            {
                // $this->_debug(3, $value);
                $temp = $value;
                $value = [];
                foreach ($temp as $key => $val)
                {
                    $value[] = $val->value;
                }

                // $this->_debug(4, $value);
            }
            else
            {
                // $this->_debug(5, $value);
                $value = null;
            }

            // $this->_debug(6, $value);
        }
        else
        {
            $value = isset($value->value) ? $value->value : "";
        }

        // $this->_debug(7, $value);
        return parent::serializeValue($value, $element);

    }

    public function getInputHtml($value, ElementInterface $element = null): string
    {

        // $this->_debug(8, $value);

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

        // $this->_debug(9, $value);
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

        // $this->_debug(10, $value);
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

        // $this->_debug(11, $value);
        if($value instanceof DynamicField)
        {
            // $this->_debug(12, $value);
            if($value->value == "" && $value->label == "")
            {
                // $this->_debug(13, $value);
                return null;
            }

            return $value;
        }
        elseif($value == "" && ! $this->isFresh($element))
        {
            // $this->_debug(14, $value);
            return null;
        }

        $this->json = $this->_parseTemplateJson();

        if($this->multi)
        {
            // $this->_debug(15, $value);
            return $this->_parseMulti($value, $element);
        }
        else
        {
            // $this->_debug(16, $value);
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

        // $this->_debug(17, $value);

        if (! is_array($value))
        {
            // $this->_debug(18, $value);
            $value = @json_decode($value, true);
            // $this->_debug(19, $value);
        }

        if (! is_array($value))
        {
            // $this->_debug(20, $value);
            $value = [];
            // $this->_debug(21, $value);
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

                    // $this->_debug(4, $value);
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

        // $this->_debug(22, $return);
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

    private function _debug($id, $message)
    {
        // echo "in -- {$id} :<br>";
        if($this->inputTemplate == "multiselect")
        {
            echo "<pre> START : {$id}";
            print_r($message);
            echo "</pre>";
        }
    }
}