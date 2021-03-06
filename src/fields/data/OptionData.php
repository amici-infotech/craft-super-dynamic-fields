<?php
namespace amici\SuperDynamicFields\fields\data;

use Craft;
use craft\base\Serializable;

class OptionData implements Serializable
{
    public $label;
    public $value;
    public $selected;
    public $valid;
    public $extras;

    public function __construct(string $label = null, string $value = null, array $extras, bool $selected, bool $valid = true)
    {
        $this->label = $label;
        $this->value = $value;
        $this->selected = $selected;
        $this->valid = $valid;
        $this->extras = $extras;
    }

    public function __toString(): string
    {
        return (string)$this->value;
    }

    public function getDefault(): bool
    {
        // return (in_array(strtolower($this->default), [true, 'true', 'yes', 'y'])) ? true : false;
        return $this->selected;
    }

    public function isAvailable(): bool
    {
        return $this->getValue() ? true : false;
    }

    public function serialize()
    {
        return $this->value;
    }

    public function getValue(): string
    {
        return (string) $this->value;
    }

    public function getLabel(): string
    {
        return (string) Craft::getAlias($this->label);
    }

    public function getExtras()
    {
        return (empty($this->extras) || ! is_array($this->extras) || count($this->extras) == 0) ? null : $this->extras;
    }

}
