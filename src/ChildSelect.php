<?php

namespace R64\NovaFields;

use R64\NovaFields\Configurable;
use Laravel\Nova\Fields\Field as NovaField;

class ChildSelect extends NovaField
{
    use Configurable;
    /**
     * The base input classes of the field.
     *
     * @var string
     */
    public $inputClasses = 'w-full form-control form-select';

    /**
     * The base index classes of the field.
     *
     * @var string
     */
    public $indexClasses = 'whitespace-no-wrap';

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-fields-child-select';


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
