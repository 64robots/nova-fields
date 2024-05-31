<?php

namespace R64\NovaFields\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Http\Requests\NovaRequest;

class FieldDownloadController extends Controller
{
    /**
     * Download the given field's contents.
     *
     * @param  Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return  Illuminate\Http\Response
     */
    public function show(NovaRequest $request): Response
    {
        $resource = $request->findResourceOrFail();

        $resource->authorizeToView($request);

        $field = $resource->detailFields($request)
                            ->where('component', 'nova-fields-row')
                            ->where('attribute', $request->query()['row'])
                            ->first()
                            ->fields
                            ->whereInstanceOf(File::class)
                            ->where('attribute', $request->field)
                            ->first();

        if (!$field) {
            abort(404);
        }

        return call_user_func(
            $field->downloadResponseCallback, $request, $request->query()['value']
        );
    }
}
