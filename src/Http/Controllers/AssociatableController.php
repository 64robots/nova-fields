<?php

namespace R64\NovaFields\Http\Controllers;

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Http\Controllers\AssociatableController as Controller;

class AssociatableController extends Controller
{
    /**
     * List the available related resources for a given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function index(NovaRequest $request)
    {
        $fields = $request->newResource()
                        ->availableFields($request);

        $field = $fields->firstWhere('attribute', $request->field);

        if(!$field) {
            $rowField = $fields->firstWhere('component', 'nova-fields-row');
            $fields = collect($rowField->meta['fields']);
            $field = $fields->firstWhere('attribute', $request->field);
        }

        $withTrashed = $this->shouldIncludeTrashed(
            $request, $associatedResource = $field->resourceClass
        );

        $query = $field->buildAssociatableQuery($request, $withTrashed);

        if ($request->input('prepopulate') == 'true') {
            $prepopulateParams = json_decode($request->input('prepopulateParams'),true);
            if ($prepopulateParams) {
                $query = $query->take($prepopulateParams['take'] ?? 10);
                if (array_key_exists('orderBy',$prepopulateParams)) {
                    foreach ($prepopulateParams['orderBy'] as $fld => $val) {
                        $query = (is_string($fld)) ? $query->orderBy($fld,$val) : $query->orderBy($val);
                    }
                }
            }
        }

        return [
            'resources' => $query->get()
                        ->mapInto($field->resourceClass)
                        ->filter->authorizedToAdd($request, $request->model())
                        ->map(function ($resource) use ($request, $field) {
                            return $field->formatAssociatableResource($request, $resource);
                        })->sortBy('display')->values(),
            'softDeletes' => $associatedResource::softDeletes(),
            'withTrashed' => $withTrashed,
        ];
    }
}
