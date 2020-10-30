<?php

namespace amici\SuperDynamicFields\fields\data;

class MultiOptionsFieldData extends \ArrayObject
{

    private $_options = [];

    public function getOptions(): array
    {
        return $this->_options;
    }

    public function setOptions(array $options)
    {
        $this->_options = $options;
    }

    public function contains($value): bool
    {
        $value = (string)$value;

        foreach ($this as $selectedValue) {
            /** @var OptionData $selectedValue */
            if ($value === $selectedValue->value) {
                return true;
            }
        }

        return false;
    }

}
