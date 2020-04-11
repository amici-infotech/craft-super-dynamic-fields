<?php
namespace amici\SuperDynamicFields\base;

use Craft;
use craft\base\ElementInterface;
use craft\base\SavableComponent;
use craft\helpers\Template as TemplateHelper;

abstract class Field extends SavableComponent
{

    public $label;
    public $default;
    public $value;
    public $extras;

    public function __toString(): string
    {
        return $this->getValue();
    }

    public function getValue(): string
    {
        return (string) $this->value;
    }

    public function getLabel(): string
    {
        return (string) $this->label;
    }

    public function getDefault(): bool
    {
        return (in_array(strtolower($this->default), ['true', 'yes', 'y'])) ? true : false;
    }

    public function getExtras()
    {
        return (empty($this->extras) || ! is_array($this->extras) || count($this->extras) == 0) ? null : $this->extras;
    }

    public function isAvailable(): bool
    {
        return $this->value && $this->value != '';
    }

}