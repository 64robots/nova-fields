<?php

namespace R64\NovaFields;

trait HasChilds
{
    /**
     * Set the config that should be applied to the child fields.
     *
     * @param  array  $childConfig
     * @return $this
     */
    public function childConfig($childConfig)
    {
        return $this->withMeta(['childConfig' => $childConfig]);
    }

    /**
     * Get the validation rules for this field.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function getRules(NovaRequest $request)
    {
        $result = [];

        if (array_key_exists('fields', $this->meta)) {
            foreach ($this->meta['fields'] as $field) {
                $fieldRules = $this->generateRules($field->getRules($request));
                $result = array_merge_recursive($result, $fieldRules);
            }
        }

        return array_merge_recursive(
            parent::getRules($request),
            $result
        );
    }

    /**
     * Get the creation rules for this field.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array|string
     */
    public function getCreationRules(NovaRequest $request)
    {
        $result = [];

        if (array_key_exists('fields', $this->meta)) {
            foreach ($this->meta['fields'] as $field) {
                $rules = $this->generateRules($field->getCreationRules($request));
                $result = array_merge_recursive($result, $rules);
            }
        }

        return array_merge_recursive(
            parent::getCreationRules($request),
            $result
        );
    }

    /**
     * Get the update rules for this field.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function getUpdateRules(NovaRequest $request)
    {
        $result = [];

        if (array_key_exists('fields', $this->meta)) {
            foreach ($this->meta['fields'] as $field) {
                $rules = $this->generateRules($field->getUpdateRules($request));
                $result = array_merge_recursive($result, $rules);
            }
        }

        return array_merge_recursive(
            parent::getUpdateRules($request),
            $result
        );
    }

}
