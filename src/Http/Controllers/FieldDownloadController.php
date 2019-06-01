<?php

namespace R64\NovaFields\Http\Controllers;

use Laravel\Nova\Fields\File;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Http\Requests\NovaRequest;

class FieldDownloadController extends Controller
{
    /**
     * Download the given field's contents.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function show(NovaRequest $request)
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

        return Storage::disk($field->disk)->download($request->query()['value']);
    }
}
