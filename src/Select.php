<?php

namespace R64\NovaFields;

use Laravel\Nova\Fields\Select as NovaSelect;

class Select extends NovaSelect
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
    public $component = 'nova-fields-select';

    /**
     * Display values using their corresponding specified labels.
     *
     * @return $this
     */
    public function displayUsingLabels()
    {
        $this->displayUsing(function ($value) {
            return collect($this->meta['options'])
                    ->where('value', $value)
                    ->first()['label'] ?? $value;
        });

        return $this->withMeta(['displayUsingLabels' => true]);
    }
}
