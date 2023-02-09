<?php

namespace R64\NovaFields;

use Laravel\Nova\Fields\Field as NovaField;

class DynamicSelect extends NovaField
{
    use Configurable;

    /**
     * The base input classes of the field.
     *
     * @var string
     */
    public $inputClasses = 'multiselect';

    /**
     * The base index classes of the field.
     *
     * @var string
     */
    public $indexClasses = 'whitespace-no-wrap';

    public $component = 'nova-fields-dynamic-select';
    protected $dependsOn = null;
    protected $dependentValues = [];
    protected $options;

    public function resolve($resource, $attribute = null)
    {
        $this->extractDependentValues($resource);

        return parent::resolve($resource, $attribute);
    }

    public function dependsOn($field)
    {
        $this->dependsOn = $field;
        $this->withMeta(['dependsOn' => $this->dependsOn]);
        $this->withMeta(['parentAttribute' => $this->dependsOn]);
        return $this;
    }

    protected function getDependsOn()
    {
        $attributes = value($this->dependsOn);

        if($attributes === null) {
            return [];
        }

        return is_string($attributes) ? [$attributes] : $attributes;
    }

    protected function extractDependentValues($model)
    {
        if($this->dependsOn === null) {
            $this->dependentValues = [];
        } else {
            $attribute = $this->dependsOn;
            $values = null;
            $values = $this->resolveAttribute($model, $attribute);
            $this->dependentValues = $values;
            $this->withMeta(['dependValues' => $this->dependentValues]);
        }
    }

    /**
     * Set the options for the select menu.
     *
     * @param  array<string|int, array<string, mixed>|string>|\Closure|callable|\Illuminate\Support\Collection  $options
     * @return $this
     */
    public function options($options)
    {
        $this->options = $options;
        $this->withMeta(['options' => $this->getOptions($this->options,false) ]);
        return $this;
    }

    public function getOptions($parameters = [],$isCallback = true)
    {
        if($isCallback){
            $options = call_user_func($this->options, $parameters);
        }else{
            $options = $parameters;
        }

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
