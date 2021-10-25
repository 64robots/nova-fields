<?php

namespace R64\NovaFields;

use Laravel\Nova\Fields\Field;

class ChildSelect extends Field
{
    public $component = 'child-select';

    protected $options;

    public function options($options)
    {
        $this->options = $options;

        return $this;
    }

    public function getOptions($parameters = [])
    {
        $options = call_user_func($this->options, $parameters);

        $result = [];
        foreach ($options as $key => $option) {
            $result[] = [
                'label' => $option,
                'value' => $key,
            ];
        }

        return $result;
    }

    public function parent($attribute)
    {
        $this->withMeta(['parentAttribute' => $attribute]);
        return $this;
    }
}
