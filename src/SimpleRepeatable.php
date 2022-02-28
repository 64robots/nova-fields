<?php

namespace R64\NovaFields;

use Laravel\Nova\Fields\Field;

class SimpleRepeatable extends Field
{
    use Configurable;


    public $inputClasses = 'w-full form-control form-select';


    public $indexClasses = 'whitespace-no-wrap';


    public $component = 'nova-fields-simple-repeatable';

    protected $fields = [];

    public function __construct($name, $attribute = null, $fields = [])
    {
        parent::__construct($name, $attribute, null);

        $this->fields($fields);
        $this->canAddRows(true);
        $this->canDeleteRows(true);
    }

    public function canAddRows($canAddRows = true)
    {
        return $this->withMeta(['canAddRows' => $canAddRows]);
    }

    public function fields($fields = [])
    {
        return $this->withMeta(['repeatableFields' => $fields]);
    }

    public function maxRows($maxRows = null)
    {
        return $this->withMeta(['maxRows' => $maxRows]);
    }

    public function canDeleteRows($canDeleteRows = true)
    {
        return $this->withMeta(['canDeleteRows' => $canDeleteRows]);
    }

    public function canDraggable($canDraggable = true)
    {
        return $this->withMeta(['canDraggable' => $canDraggable]);
    }
}
