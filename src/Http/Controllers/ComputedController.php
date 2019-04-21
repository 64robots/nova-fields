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
        $rowField = $fields->firstWhere('component', 'nova-fields-row');

        if (!$rowField) {
            return '';
        }

        $fields = collect($rowField->fields);
        $field = $fields->firstWhere('attribute', $request->field);

        if (!property_exists($field, 'computeCallback') || !$field->computeCallback) {
            return '';
        }

        return call_user_func($field->computeCallback, (object) $request->input('values'));
    }
}
