<?php

namespace R64\NovaFields;

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\BelongsTo as NovaBelongsTo;

class BelongsTo extends NovaBelongsTo
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
    public $indexClasses = 'no-underline dim text-primary font-bold';

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-fields-belongs-to';

    /**
     * The field used for display the element inside a Row Field.
     *
     * @var string
     */
    public $displayName = 'name';

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

    /**
     * Set the field that should be used to group the resources.
     *
     * @param  string  $field
     * @return $this
     */
    public function groupedBy($field)
    {
        $this->groupedBy = $field;

        return $this;
    }

    /**
     * Set the field that should be used to be displayed in a Row Field.
     *
     * @param  string  $name
     * @return $this
     */
    public function displayName($name)
    {
        $this->displayName = $name;

        return $this;
    }

    /**
     * Determine if a new related model can be created.
     *
     * @return $this
     */
    public function quickCreate()
    {
        $this->withMeta(['quickCreate' => true]);

        return $this;
    }

    /**
     * Determine if a without trashed option should be hidden.
     *
     * @return $this
     */
    public function disableTrashed()
    {
        $this->withMeta(['disableTrashed' => true]);

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
            'wrapperClasses' => $this->wrapperClasses,
            'indexClasses' => $this->indexClasses,
            'inputClasses' => $this->inputClasses,
            'fieldClasses' => $this->fieldClasses,
            'panelFieldClasses' => $this->panelFieldClasses,
            'labelClasses' => $this->labelClasses,
            'panelLabelClasses' => $this->panelLabelClasses,
            'excerptClasses' => $this->excerptClasses,
            'grouped' => !!$this->groupedBy,
            'resourceName' => $this->resourceName,
            'label' => forward_static_call([$this->resourceClass, 'label']),
            'singularLabel' => forward_static_call([$this->resourceClass, 'singularLabel']),
            'belongsToRelationship' => $this->belongsToRelationship,
            'belongsToId' => $this->belongsToId,
            'searchable' => $this->searchable,
            'displayName' => $this->displayName,
        ], $this->meta);
    }
}
