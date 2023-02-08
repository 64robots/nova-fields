<?php

namespace R64\NovaFields;

use Laravel\Nova\Fields\Field as NovaField;
use R64\NovaFields\Traits\DependsOnAnotherField;
use R64\NovaFields\Traits\HasDynamicOptions;

class DynamicSelect extends NovaField
{
    use HasDynamicOptions;
    use DependsOnAnotherField;
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

    public function resolve($resource, $attribute = null)
    {
        $this->extractDependentValues($resource);

        return parent::resolve($resource, $attribute);
    }

    public function meta()
    {
        $this->meta = parent::meta();
        return array_merge([
            'options' => $this->getOptions($this->dependentValues),
            'dependsOn' => $this->getDependsOn(),
            'dependValues' => $this->dependentValues,
        ], $this->meta);
    }
}
