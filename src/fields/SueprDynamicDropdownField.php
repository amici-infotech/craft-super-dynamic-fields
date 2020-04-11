<?php
namespace amici\SuperDynamicFields\fields;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use yii\db\Schema;

use amici\SuperDynamicFields\SuperDynamicFields;
use amici\SuperDynamicFields\assetbundles\SuperDynamicFieldsAsset;

class SueprDynamicDropdownField extends Field
{

    public $template;

	public static function displayName(): string
    {
        return Craft::t('super-dynamic-fields', 'Dropdown [Super Dyanmic Fields]');
    }

    public static function defaultSelectionLabel(): string
    {
        return Craft::t('super-dynamic-fields', 'Select JSON template');
    }

    public function getContentColumnType(): string
    {
        return Schema::TYPE_TEXT;
    }

    protected function optionsSettingLabel(): string
    {
        return Craft::t('super-dynamic-fields', 'Field Options');
    }

    public function getInputHtml($value, ElementInterface $element = null): string
    {

        $view = Craft::$app->getView();
        $mode = $view->getTemplateMode();

        $id             = Craft::$app->getView()->formatInputId($this->handle);
        $nameSpacedId   = Craft::$app->getView()->namespaceInputId($id);

        $options    = [];
        $variables  = [];
        $template  = "";
        $genError   = "";

        try
        {

            $view->setTemplateMode($view::TEMPLATE_MODE_SITE);
            $template = Craft::$app->getView()->renderTemplate($this->template, $variables);
            $view->setTemplateMode($mode);

            $json = json_decode($template, true);
            if(is_array($json))
            {
                foreach ($json as $key => $val)
                {
                    if(isset($val['value']) && isset($val['label']))
                    {
                        $options[$val['value']] = $val['label'];
                    }
                }
            }
            else
            {
                $genError = "Output string is not a valid JSON";
            }

        }
        catch (\ErrorException $e)
        {
            $view->setTemplateMode($mode);
            $genError = $e->getMessage();
        }
        catch (\Exception $e)
        {
            $view->setTemplateMode($mode);
            $genError = $e->getMessage();
        }

        $view->registerAssetBundle(SuperDynamicFieldsAsset::class);
        return Craft::$app->getView()->renderTemplate('super-dynamic-fields/_field/input', [
            'id'        => $id,
            'name'      => $this->handle,
            'options'   => $options,
            'value'     => $value,
            'genError'  => $genError,
            'template'  => $template
        ]);

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

}