<?php

namespace R64\NovaFields;

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\BelongsTo as NovaBelongsTo;

class BelongsTo extends NovaBelongsTo
{
    use Configurable;

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-fields-belongs-to';

    protected $groupedBy = null;

    /**
     * Format the given associatable resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  mixed  $resource
     * @return array
     */
    public function formatAssociatableResource(NovaRequest $request, $resource)
    {
        $relation = explode(".", $this->groupedBy);

        $groupedBy = count($relation) == 2
                   ? $resource->{$relation[0]}->{$relation[1]}
                   : $resource->{$this->groupedBy};

        return array_filter([
            'groupedBy' => $groupedBy,
            'avatar' => $resource->resolveAvatarUrl($request),
            'display' => $this->formatDisplayValue($resource),
            'value' => $resource->getKey(),
        ]);
    }

    public function groupedBy($field)
    {
        $this->groupedBy = $field;

        return $this;
    }


    /**
     * Get additional meta information to merge with the field payload.
     *
     * @return array
     */
    public function meta()
    {
        return array_merge([
            'grouped' => !!$this->groupedBy,
            'resourceName' => $this->resourceName,
            'label' => forward_static_call([$this->resourceClass, 'label']),
            'singularLabel' => forward_static_call([$this->resourceClass, 'singularLabel']),
            'belongsToRelationship' => $this->belongsToRelationship,
            'belongsToId' => $this->belongsToId,
            'searchable' => $this->searchable,
        ], $this->meta);
    }
}
