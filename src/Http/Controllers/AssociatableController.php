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
        $prepopulateCollection = null;
        $sorted = false;

        if ($request->input('prepopulate') == 'true') {
            $prepopulateParams = json_decode($request->input('prepopulateParams'),true);
            if ($prepopulateParams) {
                if ($request->input('current')) {
                    $prepopulateQuery = $field->buildAssociatableQuery($request->duplicate()->merge([
                        'first' => false,
                        'current' => null
                    ]), $withTrashed);
                    $prepopulateQuery = static::modifyQueryToPrepopulate($prepopulateQuery, $prepopulateParams);
                    $prepopulateCollection = $prepopulateQuery->get();
                } else {
                    $query = static::modifyQueryToPrepopulate($query, $prepopulateParams);   
                }
                $sorted = array_key_exists('orderBy',$prepopulateParams);
            }
        }

        $collection = $query->get();
        if ($prepopulateCollection) {
            $collection = $collection->merge($prepopulateCollection);
        }

        $resources = $collection
            ->mapInto($field->resourceClass)
            ->filter->authorizedToAdd($request, $request->model())
            ->map(function ($resource) use ($request, $field) {
                return $field->formatAssociatableResource($request, $resource);
            })
        ;
        if (!$sorted) {
            $resources = $resources->sortBy('display');
        }

        return [
            'resources' => $resources->values(),
            'softDeletes' => $associatedResource::softDeletes(),
            'withTrashed' => $withTrashed,
        ];
    }
    
    protected static function modifyQueryToPrepopulate($query, $prepopulateParams)
    {
        $query = $query->take($prepopulateParams['take'] ?? 10);
        if (array_key_exists('orderBy',$prepopulateParams)) {
            $query->getQuery()->orders = null;
            foreach ($prepopulateParams['orderBy'] as $fld => $val) {
                $query = (is_string($fld)) ? $query->orderBy($fld,$val) : $query->orderBy($val,'asc');
            }
        }
        return $query;
    }
}
