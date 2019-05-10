<?php

namespace R64\NovaFields\Http\Controllers;

use Laravel\Nova\Http\Requests\NovaRequest;

class ComputedController
{
    /**
     * Compute the value.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function index(NovaRequest $request)
    {
        $fields = $request->newResource()->availableFields($request);

        $rowField = $fields->first(function($field) use ($request) {
            return ($field->component === 'nova-fields-row' || $field->component === 'nova-fields-json' ) &&
                    $field->attribute === $request->input('parentAttribute');
        });

        if (!$rowField) {
            return '';
        }

        $fields = collect($rowField->fields);
        $field = $fields->firstWhere('attribute', $request->field);

        $cb = $request->input('computeOptions') ? $field->computeOptionsCallback : $field->computeCallback;

        if (!is_callable($cb)) {
            return;
        }

        $value = call_user_func($cb, new ComputedValues($request->input('values')));

        if ($request->input('computeOptions')) {
            return collect($value ?? [])->map(function ($label, $value) {
                return is_array($label) ? $label + ['value' => $value] : ['label' => $label, 'value' => $value];
            })->values()->all();
        }

        return $value;
    }
}
