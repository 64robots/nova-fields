<?php

namespace R64\NovaFields;

use Laravel\Nova\Fields\Field as NovaField;

class Items extends NovaField
{

    use Configurable;

    /**
     * The base input classes of the field.
     *
     * @var string
     */
    public $inputClasses = 'flex-1 form-control form-input form-input-bordered';

    /**
     * The base index classes of the field.
     *
     * @var string
     */
    public $indexClasses = 'pb-4';


    public $items = [];
    public $component = 'nova-fields-items';
    public $placeholder = "Add a new item";
    public $inputType = 'text';
    public $max = false;
    public $fullWidth = false;
    public $maxHeight = false;
    public $draggable = false;
    public $hideCreateButton = false;
    public $createButtonValue = "Add";
    public $deleteButtonValue = "x";
    public $listFirst = false;
    public $detailItemComponent = 'detail-nova-fields-item';

    public function resolve($resource, $attribute = null)
    {
        parent::resolve($resource, $attribute);

        $this->fillUsing(function($request, $model, $attribute, $requestAttribute) {
            $model->$attribute = $this->isNullValue($request->$attribute) ? null : json_decode($request->$attribute, true);
        });

        $this->withMeta([
            'max' => $this->max,
            'items' => $this->items,
            'listFirst' => $this->listFirst,
            'inputType' => $this->inputType,
            'draggable' => $this->draggable,
            'fullWidth' => $this->fullWidth,
            'maxHeight' => $this->maxHeight,
            'placeholder' => $this->placeholder,
            'hideCreateButton' => $this->hideCreateButton,
            'createButtonValue' => $this->createButtonValue,
            'deleteButtonValue' => $this->deleteButtonValue,
            'detailItemComponent' => $this->detailItemComponent,
        ]);
    }

    public function rules($rules)
    {
        if (!is_array($rules)) {
            abort(500, 'Nova Items Field requires array of validation rules');
        }

        $this->rules = [ new ArrayRules($rules) ];

        return $this;
    }

    public function values($values)
    {
        if (is_array($values) && count($values)) {
            $this->items = $values;
        }

        return $this;
    }

    public function max($max)
    {
        $this->max = $max;

        return $this;
    }

    public function hideCreateButton($hideCreateButton = true)
    {
        $this->hideCreateButton = $hideCreateButton;

        return $this;
    }

    public function inputType($inputType)
    {
        $this->inputType = $inputType;

        return $this;
    }

    public function fullWidth($fullWidth = true)
    {
        $this->fullWidth = $fullWidth;

        return $this;
    }

    public function maxHeight($maxHeight)
    {
        $this->maxHeight = $maxHeight;

        return $this;
    }

    public function draggable($draggable = true)
    {
        $this->draggable = $draggable;

        return $this;
    }

    public function placeholder($placeholder)
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    public function listFirst($listFirst = true)
    {
        $this->listFirst = $listFirst;

        return $this;
    }

    public function deleteButtonValue($deleteButtonValue)
    {
        $this->deleteButtonValue = $deleteButtonValue;

        return $this;
    }

    public function createButtonValue($createButtonValue)
    {
        $this->createButtonValue = $createButtonValue;

        return $this;
    }

    public function detailItemComponent($detailItemComponent)
    {
        $this->detailItemComponent = $detailItemComponent;

        return $this;
    }
}
