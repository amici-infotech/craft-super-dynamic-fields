<?php
namespace amici\SuperDynamicFields\base;

use Craft;
use yii\db\Schema;
use GraphQL\Type\Definition\Type;

use craft\base\ElementInterface;
use craft\fields\conditions\OptionsFieldConditionRule;
use craft\gql\arguments\OptionField as OptionFieldArguments;
use craft\helpers\Cp;
use craft\helpers\Db;
use craft\helpers\Json;
use craft\helpers\StringHelper;

use amici\SuperDynamicFields\assetbundles\SuperDynamicFieldsSettingsAsset;
use amici\SuperDynamicFields\fields\data\OptionData;
use amici\SuperDynamicFields\fields\data\SingleOptionFieldData;
use amici\SuperDynamicFields\fields\data\MultiOptionsFieldData;
use amici\SuperDynamicFields\resolvers\OptionField as OptionFieldResolver;

trait FieldTrait
{

    public ?string $template = "";
    public ?bool $cachedOptions = true;
    public ?string $templateData = "";
    public $json = "";
    public ?string $genError = "";
    public $element;

    // public ?array $options;

    protected function optionsSettingLabel(): string
    {
        return Craft::t('super-dynamic-fields', 'JSON Template');
    }

    public static function defaultSelectionLabel(): string
    {
        return Craft::t('super-dynamic-fields', 'Select JSON template');
    }

    public function getContentColumnType(): string
    {
        if (static::$multi) {
            return Schema::TYPE_TEXT;

            /*// See how much data we could possibly be saving if everything was selected.
            $length = 0;

            foreach ($this->options() as $option) {
                if (!empty($option['value'])) {
                    // +3 because it will be json encoded. Includes the surrounding quotes and comma.
                    $length += strlen($option['value']) + 3;
                }
            }

            // Add +2 for the outer brackets and -1 for the last comma.
            return Db::getTextualColumnTypeByContentLength($length + 1);*/
        }

        return Schema::TYPE_STRING;
    }

    public static function hasContentColumn(): bool
    {
        return true;
    }

    public function getSettingsHtml(): ?string
    {
        $view = Craft::$app->getView();
        $view->registerAssetBundle(SuperDynamicFieldsSettingsAsset::class);

        return Craft::$app->getView()->renderTemplate('super-dynamic-fields/_field/settings', [
            'settings' => $this,
            'label' => $this->optionsSettingLabel()
        ]);
    }

    protected function defineRules(): array
    {
        $rules = parent::defineRules();
        $rules[] = [['template'], 'required'];
        return $rules;
    }

    public function getElementValidationRules(): array
    {
        return [];
        // Removed range validation for options as template data may vary per element group
    }

    public function normalizeValue($value, ?ElementInterface $element = null): mixed
    {
        if ($value instanceof SingleOptionFieldData || $value instanceof MultiOptionsFieldData) {
            return $value;
        }

        if($this->templateData == "" || ! $this->cachedOptions) {
            $this->json = $this->_parseTemplateJson($element);
        }

        if (is_string($value) && (
            str_starts_with($value, '[') ||
            str_starts_with($value, '{')
        )) {
            $value = Json::decodeIfJson($value);
        } elseif ($value === '' && static::$multi) {
            $value = [];
        } elseif (is_string($value) && strtolower($value) === '__blank__') {
            $value = '';
        } elseif ($value === null && $this->isFresh($element)) {
            $value = $this->defaultValue();
        }

        // Normalize to an array of strings
        $selectedValues = [];
        foreach ((array)$value as $val) {
            $val = (string)$val;
            if (str_starts_with($val, 'base64:')) {
                $val = base64_decode(StringHelper::removeLeft($val, 'base64:'));
            }
            $selectedValues[] = $val;
        }

        $selectedBlankOption = false;
        $options = [];
        $optionValues = [];
        $optionLabels = [];
        $optionExtras = [];

        foreach ($this->options() as $option) {
            if (! isset($option['optgroup'])) {
                $selected = $this->isOptionSelected($option, $value, $selectedValues, $selectedBlankOption);

                $extras  = $option;
                unset($extras['value']);
                unset($extras['label']);
                unset($extras['default']);

                $options[] = new OptionData($option['label'], $option['value'], $extras, $selected, true);
                $optionValues[] = (string)$option['value'];
                $optionLabels[] = (string)$option['label'];
                $optionExtras[] = $extras;
            }
        }

        if (static::$multi)
        {

            // Convert the value to a MultiOptionsFieldData object
            $selectedOptions = [];
            foreach ($selectedValues as $selectedValue)
            {
                $index = array_search($selectedValue, $optionValues, true);
                $valid = $index !== false;
                $label = $valid ? $optionLabels[$index] : null;
                $extras = $valid ? $optionExtras[$index] : null;
                $selectedOptions[] = new OptionData($label, $selectedValue, $extras, true, $valid);
            }

            $value = new MultiOptionsFieldData($selectedOptions);

        }
        elseif(! empty($selectedValues))
        {

            // Convert the value to a SingleOptionFieldData object
            $extras = [];
            $selectedValue = reset($selectedValues);
            $index = array_search($selectedValue, $optionValues, true);
            $valid = $index !== false;
            $label = $valid ? $optionLabels[$index] : null;
            $extras = $valid ? $optionExtras[$index] : null;
            $value = new SingleOptionFieldData($label, $selectedValue, $extras, true, $valid);
        }
        else
        {
            $value = new SingleOptionFieldData(null, null, [], true, true);
        }

        $value->setOptions($options);
        return $value;

    }

    /**
     * Check if given option should be marked as selected.
     *
     * @param array $option
     * @param mixed $value
     * @param array $selectedValues
     * @param bool $selectedBlankOption
     * @return bool
     */
    protected function isOptionSelected(array $option, mixed $value, array &$selectedValues, bool &$selectedBlankOption): bool
    {
        return in_array($option['value'], $selectedValues, true);
    }

    public function serializeValue($value, ElementInterface $element = null): mixed
    {
        if ($value instanceof MultiOptionsFieldData) {
            $serialized = [];
            foreach ($value as $selectedValue) {
                /** @var OptionData $selectedValue */
                $serialized[] = $selectedValue->value;
            }
            return $serialized;
        }

        return parent::serializeValue($value, $element);
    }

    /**
     * Base64-encodes a value.
     *
     * @param OptionData|MultiOptionsFieldData|string|null $value
     * @return string|array|null
     * @since 4.0.6
     */
    protected function encodeSdfValue(OptionData|MultiOptionsFieldData|string|null $value): string|array|null
    {
        if ($value instanceof MultiOptionsFieldData) {
            /** @var OptionData[] $options */
            $options = (array)$value;
            return array_map(fn(OptionData $value) => $this->encodeValue($value), $options);
        }

        if ($value instanceof OptionData) {
            $value = $value->value;
        }

        if ($value === null || $value === '') {
            return $value;
        }

        return sprintf('base64:%s', base64_encode($value));
    }

    public function isValueEmpty($value, ElementInterface $element = null): bool
    {
        /** @var MultiOptionsFieldData|SingleOptionFieldData $value */
        if ($value instanceof SingleOptionFieldData || $value instanceof OptionData) {
            return $value->value === null || $value->value === '';
        }

        return count($value) === 0;
    }

    protected function options(): array
    {

        $this->options = [];
        /* if($this->templateData == "" || ! $this->cachedOptions)
        {
            $this->json = $this->_parseTemplateJson();
        } */

        if(is_array($this->json))
        {
            foreach ($this->json as $key => $val)
            {
                if(isset($option['isOptgroup']) && $option['isOptgroup'] != "")
                {
                    $this->options[] = [
                        'optgroup' => $option['isOptgroup'],
                    ];
                }
                elseif(isset($val['value']) && isset($val['label']))
                {
                    unset($this->options['isOptgroup']);
                    $this->options[] = $val;
                }
            }
        }

        return $this->options;

    }

    public function getContentGqlType(): array
    {
        return [
            'name' => $this->handle,
            'type' => static::$multi ? Type::listOf(Type::string()) : Type::string(),
            'args' => OptionFieldArguments::getArguments(),
            'resolve' => OptionFieldResolver::class . '::resolve',
        ];
    }

    private function _parseTemplateJson(ElementInterface $element = null): mixed
    {
        $view       = Craft::$app->getView();
        $path       = $view->getTemplatesPath();

        $variables  = [
            // 'current' => $this
            'element' => $element
        ];
        $json       = false;

        try
        {

            $json = false;
            if($this->template)
            {
                $view->setTemplatesPath(Craft::$app->getPath()->getSiteTemplatesPath());
                $this->templateData = $view->renderTemplate($this->template, $variables, $view::TEMPLATE_MODE_SITE);
                $view->setTemplatesPath($path);
                $json = json_decode($this->templateData, true);
            }

            if(! is_array($json))
            {
                $this->genError = "Output string is not a valid JSON";
            }

        }
        catch (\ErrorException $e)
        {
            $view->setTemplatesPath($path);
            $this->genError = $e->getMessage();
        }
        catch (\Exception $e)
        {
            $view->setTemplatesPath($path);
            $this->genError = $e->getMessage();
        }

        return $json;
    }

    public function getElementConditionRuleType(): array|string|null
    {
        $this->normalizeValue("");
        return OptionsFieldConditionRule::class;
    }

}