<?php

namespace R64\NovaFields;

use R64\NovaFields\Configurable;
use JsonSerializable;
use Laravel\Nova\Fields\Slug as NovaSlug;

class Slug extends NovaSlug implements JsonSerializable
{
    use Configurable;

    /**
     * The base input classes of the field.
     *
     * @var string
     */
    public $inputClasses = 'w-full form-control form-input form-input-bordered';

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
    public $component = 'nova-fields-slug';

    protected $showCustomize = true;

    public function showCustomize($value = true){
        $this->showCustomize = $value;
        return $this;
    }

    public function jsonSerialize() :array
    {
        return array_merge(parent::jsonSerialize(),[
            'showCustomize' => $this->showCustomize,
        ]);
    }
}
