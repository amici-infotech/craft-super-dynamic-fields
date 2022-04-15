<?php

namespace amici\SuperDynamicFields\fields\data;

class SingleOptionFieldData extends OptionData
{

    private array $_options = [];

    public function getOptions(): array
    {
        return $this->_options;
    }

    public function setOptions(array $options)
    {
        $this->_options = $options;
    }

}
