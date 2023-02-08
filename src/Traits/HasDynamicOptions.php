<?php

namespace R64\NovaFields\Traits;

use Closure;

trait HasDynamicOptions
{
    protected $options = [];

    public function options($options)
    {
        $this->options = $options;

        return $this;
    }

    public function getOptions($parameters = [])
    {
        $options = $this->options instanceof Closure
            ? call_user_func($this->options, $parameters)
            : $this->options;

        $result = [];
        foreach ($options as $key => $option) {
            $result[] = [
                'value' => $key,
                'label' => $option,
            ];
        }

        return $result;
    }
}
